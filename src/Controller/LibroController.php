<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Form\IsbnType;
use App\Form\LibroEditType;
use App\Form\LibroType;
use App\Repository\AlertaRepository;
use App\Repository\LibroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Vich\UploaderBundle\FileAbstraction\ReplacingFile;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @Route("/libro")
 */
class LibroController extends AbstractController
{

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @Route("/", name="app_libro_index", methods={"GET"})
     */
    public function index(LibroRepository $libroRepository): Response
    {
        return $this->render('libro/index.html.twig', [
            'libros' => $libroRepository->findAll(),

        ]);
    }

    /**
     * @Route("/alerta/{alerta}", name="app_libro_alerta", methods={"GET"})
     */
    public function alerta(AlertaRepository $alertaRepository, $alerta): Response
    {
        // Find the alerta entity
        $alerta = $alertaRepository->find($alerta);

        if (!$alerta) {
            throw $this->createNotFoundException('No alerta found id ' . $alerta);
        }

        // Access the associated books
        $libros = $alerta->getLibro();
        return $this->render('libro/alerta.html.twig', [
            'libros' => $libros,
            'alerta' => $alerta,

        ]);
    }

    /**
     * @Route("/new", name="app_libro_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LibroRepository $libroRepository): Response
    {

        $data = [
            'isbn' => '',
            'title' => '',
            'authors' => '',
            'publishedDate' => '',
            'publisher' => '',
        ];

        $imageUrl = null;
        $formSubmitted = false;
        $isbn = null;
        $libro = new Libro();

        $form = $this->createForm(LibroType::class, $data);





        $subForm = $this->createForm(IsbnType::class); // Crear el subformulario

        $form->handleRequest($request);
        $subForm->handleRequest($request);

        if ($subForm->isSubmitted() && $subForm->isValid() && $request->request->has('sub_form_submit')) {

            $isbn = $subForm->get('isbn')->getData();
            $formSubmitted = true;

            if ($isbn) {
                try {
                    $response = $this->httpClient->request('GET', 'https://www.googleapis.com/books/v1/volumes', [
                        'query' => ['q' => 'isbn:' . $isbn],
                    ]);
                    $bookData = $response->toArray();

                 //   print_r($bookData);
                    if (isset($bookData['items'][0]['volumeInfo'])) {
                        $volumeInfo = $bookData['items'][0]['volumeInfo'];
                        $imageLinks = $volumeInfo['imageLinks'] ?? null;

                        if ($imageLinks && isset($imageLinks['thumbnail'])) {
                            $imageUrl = $imageLinks['thumbnail']; // Obtiene la URL de la imagen en miniatura

                            // Fetch the image content
                            $client = HttpClient::create();
                            $response = $client->request('GET', $imageUrl);
                          //  $response = $client->request('GET', 'https://covers.openlibrary.org/b/isbn/'.$isbn.'.jpg');
                            $imageContent = $response->getContent();

                            // Define the local path where the image will be saved
                            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/alerta/';
                            $fileName = $isbn.'.jpg';
                            $filePath = $uploadDir . $fileName;

                            // Save the image locally
                            file_put_contents($filePath, $imageContent);
                        }

                        $data = [
                            'isbn' => $isbn,
                            'title' => $volumeInfo['title'] ?? '',
                            'authors' => isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : '',
                            'publishedDate' => $volumeInfo['publishedDate'] ?? '',
                            'publisher' => $volumeInfo['publisher'] ?? '',
                        ];

                        $publishedDate = $volumeInfo['publishedDate'] ?? null;

                        if ($publishedDate) {
                            // Extraer el año de la fecha de publicación
                            $publicationYear = substr($publishedDate, 0, 4);
                        } else {
                            $publicationYear = 'Unknown Year';
                        }



                        // Re-crear el formulario con los datos obtenidos
                        $form = $this->createForm(LibroType::class, $data);
                        $form->get('titulo')->setData($data["title"]);
                        $form->get('autor')->setData($data["authors"]);
                        $form->get('editorial')->setData($data["publisher"]);
                        $form->get('anio')->setData($publicationYear);


                    }



                } catch (\Exception $e) {
                    $this->addFlash('error', 'Unable to fetch book data: ' . $e->getMessage());
                }



            }

        }

        if ($form->isSubmitted() && $form->isValid() && $request->request->has('main_form_submit')) {


            $isbn = $request->request->get('isbn');

            $libro->setTitulo($form->get('titulo')->getData());
            $libro->setAutor($form->get('autor')->getData());
            $libro->setEditorial($form->get('editorial')->getData());
            $libro->setUrl($form->get('url')->getData());
            $libro->setClasificacion($form->get('clasificacion')->getData());
            $libro->setAnio($form->get('anio')->getData());
            $libro->setAlerta($form->get('alerta')->getData());
            $libro->setIsbn($form->get('isbn')->getData());
            $libro->setSlide($form->get('slide')->getData());


            if ( $form->get('portadaFile')->getData() == null){
                $libro->setPortadaName($isbn.'.jpg');            }
            else {
                $libro->setPortadaFile($form->get('portadaFile')->getData());
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/alerta/';
                $fileName = $isbn.'.jpg';
                $filePath = $uploadDir . $fileName;
                unlink($filePath);
            }

            $libroRepository->add($libro, true);

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('libro/new.html.twig', [
            'libro' => $libro,
            'form' => $form,
            'subForm' => $subForm, // Pasar el subformulario a la vista
            'bookData'=> $data,
            'imageUrl' => $imageUrl,
            'formSubmitted' => $formSubmitted,
            'is_edit'=> false,

        ]);
    }

    /**
     * @Route("/{slug}", name="app_libro_show", methods={"GET"})
     */
    public function show(Libro $libro): Response
    {
        return $this->render('libro/show.html.twig', [
            'libro' => $libro,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_libro_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Libro $libro, LibroRepository $libroRepository): Response
    {


        $form = $this->createForm(LibroEditType::class, $libro);
        $form->handleRequest($request);

        $subForm = $this->createForm(IsbnType::class); // Crear el subformulario
        $subForm->handleRequest($request);

        $formSubmitted = false;



        if ($form->isSubmitted() && $form->isValid()) {
            $libro->setModified(new \DateTime());

            $libroRepository->add($libro, true);

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('libro/edit.html.twig', [
            'libro' => $libro,
            'form' => $form,
            'subForm'=> $subForm,
            'formSubmitted' => $formSubmitted,
            'bookData'=>['isbn'=>''],
            'is_edit' => true,


        ]);
    }

    /**
     * @Route("/{slug}", name="app_libro_delete", methods={"POST"})
     */
    public function delete(Request $request, Libro $libro, LibroRepository $libroRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libro->getSlug(), $request->request->get('_token'))) {
            $libroRepository->remove($libro, true);

        }

        return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
    }
}
