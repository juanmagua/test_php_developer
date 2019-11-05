<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Event;
use App\Repository\EventRepository;
use FOS\RestBundle\View\View;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class EventController
 *
 * @Route("/api")
 */
class EventController extends FOSRestController
{

    /**
     * @Rest\Get("/v1/event.{_format}", name="event_list_all", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets all events for current logged user."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to get all user events."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The event ID"
     * )
     *
     *
     * @SWG\Tag(name="Event")
     */
    public function getAllEventAction(Request $request, EventRepository $eventRepository) {

        $serializer = $this->get('jms_serializer');
        
        $events = [];
        
        $message = "";

        try {
            $code = 200;
            $error = false;

            $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

            $events = $eventRepository->findBy([
                "user" => $userId,
            ]);

            if (is_null($events)) {
                $events = [];
            }

            $response = $events;

            return new Response($serializer->serialize($response, "json"));    

        } catch (Exception $ex) {

            $response = [
                'code' => '500',
                'error' => $error,
                'data' => "An error has occurred trying to get all events - Error: {$ex->getMessage()}",
            ];
            return new Response($serializer->serialize($response, "json"));
        }

       
    }

    /**
     * @Rest\Get("/v1/event/{id}.{_format}", name="event_list", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Gets event info based on passed ID parameter."
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="The event with the passed ID parameter was not found or doesn't exist."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The event ID"
     * )
     *
     *
     * @SWG\Tag(name="Event")
     */
    public function getEventAction(Request $request, EventRepository $eventRepository, $id) {
        $serializer = $this->get('jms_serializer');

        $em = $this->getDoctrine()->getManager();

        $event = [];

        $message = "";

        try {
            $code = 200;
            $error = false;

            $event_id = $id;
            $event = $eventRepository->find($event_id);

            if (is_null($event)) {
                $code = 500;
                $error = true;
                $message = "The event does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to get the current event - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $event : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Post("/v1/event.{_format}", name="event_add", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=201,
     *     description="event was added successfully"
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error was occurred trying to add new event"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The event name",
     *     schema={}
     * )
     *
     * @SWG\Tag(name="Event")
     */
    public function addEventAction(Request $request, EntityManagerInterface $em) {
        $serializer = $this->get('jms_serializer');
       
        $event = [];
       
        $message = "";

        try {
           $code = 201;
           $error = false;
           $name = $request->request->get("name", null);
           $user = $this->getUser();

           if (!is_null($name)) {
               $event = new event();
               $event->setName($name);
               $event->setUser($user);

               $em->persist($event);
               $em->flush();

           } else {
               $code = 500;
               $error = true;
               $message = "An error has occurred trying to add new event - Error: You must to provide a event name";
           }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to add new event - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 201 ? $event : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Put("/v1/event/{id}.{_format}", name="event_edit", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="The event was edited successfully."
     * )
     *
     * @SWG\Response(
     *     response=500,
     *     description="An error has occurred trying to edit the event."
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The event ID"
     * )
     *
     * @SWG\Parameter(
     *     name="name",
     *     in="body",
     *     type="string",
     *     description="The event name",
     *     schema={}
     * )
     * 
     *  @SWG\Parameter(
     *     name="created",
     *     in="body",
     *     type="string",
     *     description="The event created",
     *     schema={}
     * )
     *
     *
     * @SWG\Tag(name="Event")
     */
    public function editEventAction(Request $request, EntityManagerInterface $em, EventRepository $eventRepository, $id) {
        $serializer = $this->get('jms_serializer');
       
        $event = [];
       
        $message = "";

        try {
            $code = 200;
            $error = false;
            $name = $request->request->get("name", null);
            $created = new \DateTime($request->request->get("created", null));
            //$created = $created->format('Y-m-d h:i:s');
            $event = $eventRepository->find($id);

            if (!is_null($name) && !is_null($event)) {
                $event->setName($name);
                $event->setCreated($created);
                $em->persist($event);
                $em->flush();

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to add new event - Error: You must to provide a event name or the event id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to edit the current event - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $code == 200 ? $event : $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

    /**
     * @Rest\Delete("/v1/event/{id}.{_format}", name="event_remove", defaults={"_format":"json"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="event was successfully removed"
     * )
     *
     * @SWG\Response(
     *     response=400,
     *     description="An error was occurred trying to remove the event"
     * )
     *
     * @SWG\Parameter(
     *     name="id",
     *     in="path",
     *     type="string",
     *     description="The event ID"
     * )
     *
     * @SWG\Tag(name="Event")
     */
    public function deleteEventAction(Request $request, EntityManagerInterface $em, EventRepository $eventRepository, $id) {
        $serializer = $this->get('jms_serializer');
       
        try {
            $code = 200;
            $error = false;
            $event = $eventRepository->find($id);

            if (!is_null($event)) {
                $em->remove($event);
                $em->flush();

                $message = "The event was removed successfully!";

            } else {
                $code = 500;
                $error = true;
                $message = "An error has occurred trying to remove the currrent event - Error: The event id does not exist";
            }

        } catch (Exception $ex) {
            $code = 500;
            $error = true;
            $message = "An error has occurred trying to remove the current event - Error: {$ex->getMessage()}";
        }

        $response = [
            'code' => $code,
            'error' => $error,
            'data' => $message,
        ];

        return new Response($serializer->serialize($response, "json"));
    }

       
}