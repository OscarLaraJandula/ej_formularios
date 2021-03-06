<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Alumno;
use AppBundle\Form\Type\AlumnoType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AlumnoController extends Controller
{
    /**
     * @Route("/alumnos", name="listar_alumnos")
     */
    public function indexAction()
    {

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $alumnos = $em->createQueryBuilder()
            ->select('a')
            ->addSelect('g')
            ->from('AppBundle:Alumno', 'a')
            ->join('a.grupo', 'g')
            ->orderBy('a.apellidos')
            ->getQuery()
            ->getResult();
        dump($alumnos);


        return $this->render('alumno/listar.html.twig', [
            'alumnos' => $alumnos
        ]);
    }

    /**
     * @Route("/alumnos/modificar/{id}", name="modificar_alumnos")
     */
    public function formAlumnoAction(Request $request, Alumno $alumno)
    {

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $form =$this->createForm(AlumnoType::class, $alumno);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('alumno/form.html.twig', [
            'alumno' => $alumno,
            'formulario' => $form->createView()
        ]);
    }
}
