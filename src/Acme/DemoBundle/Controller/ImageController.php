<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Form\ImageType;
use Acme\DemoBundle\Model\Image;

use FOS\RestBundle\Util\Codes;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\Annotations\Post;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for images
 *
 * @package Acme\DemoBundle\Controller
 */
class ImageController extends FOSRestController
{
    /**
     * return \Acme\DemoBundle\ImageManager
     */
    public function getImageManager()
    {
        return $this->get('acme.demo.image_manager');
    }

    /**
     * List all images.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     *
     * @return array
     */
    public function getImagesAction(Request $request)
    {
        $images = $this->getImageManager()->fetch();

        return array('items' => $images);
    }

    /**
     * Get a single image.
     *
     * @ApiDoc(
     *   output = "Acme\DemoBundle\Model\Image",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the note is not found"
     *   }
     * )
     *
     * @Annotations\View(templateVar="image")
     *
     * @param Request $request the request object
     * @param int     $id      the note id
     *
     * @return array
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function getImageAction(Request $request, $id)
    {
        $image = $this->getImageManager()->get($id);
        if (false === $image) {
            throw $this->createNotFoundException("Image does not exist.");
        }

        $view = new View($image);
        $group = $this->container->get('security.context')->isGranted('ROLE_API') ? 'restapi' : 'standard';
        $view->getSerializationContext()->setGroups(array('Default', $group));

        return $view;
    }

    /**
     * Creates a new image from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\ImageType",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Annotations\View(
     *   template = "AcmeDemoBundle:Image:newImage.html.twig",
     *   statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request the request object
     *
     * @return FormTypeInterface|RouteRedirectView
     */
    public function postImageAction(Request $request)
    {
        $image = new Image();
        $form = $this->createForm(new ImageType(), $image);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getImageManager()->set($image);

            return $this->routeRedirectView('get_image', array('id' => $image->id));
        }

        return array(
            'form' => $form
        );
    }

    /**
     * Update existing image from the submitted data or create a new image at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Acme\DemoBundle\Form\ImageType",
     *   statusCodes = {
     *     201 = "Returned when a new resource is created",
     *     204 = "Returned when successful",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     *
     * @Post("/images/{id}")
     *
     * @Annotations\View(
     *   template="AcmeDemoBundle:Image:editImage.html.twig",
     *   templateVar="form"
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the note id
     *
     * @return FormTypeInterface|RouteRedirectView
     *
     * @throws NotFoundHttpException when note not exist
     */
    public function postExistingImageAction(Request $request, $id)
    {
        $image = $this->getImageManager()->get($id);
        if (false === $image) {
            throw $this->createNotFoundException("Image does not exist.");
        }

        $image->file = null;
        $form = $this->createForm(new ImageType(), $image);

        $form->submit($request);
        if ($form->isValid()) {
            $this->getImageManager()->set($image);

            return $this->routeRedirectView('get_image', array('id' => $image->id), 200);
        }

        return $form;
    }

    /**
     * Removes a image.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes={
     *     204="Returned when successful"
     *   }
     * )
     *
     * @param Request $request the request object
     * @param int     $id      the image id
     *
     * @return RouteRedirectView
     */
    public function deleteImageAction(Request $request, $id)
    {
        $this->getImageManager()->remove($id);

        // There is a debate if this should be a 404 or a 204
        // see http://leedavis81.github.io/is-a-http-delete-requests-idempotent/
        return $this->routeRedirectView('get_images', array(), Codes::HTTP_NO_CONTENT);
    }
}
