<?php

namespace App\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;


final class MessageController extends AbstractController
{

    #[Route('/message', name: 'create_message', methods: ['POST'])]
    public function createMessage(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
        ): Response {
        // Deserialize JSON into Message entity
        $message = $serializer->deserialize($request->getContent(), Message::class, 'json');

        // Validate the data inside the message entity
        $errors = $validator->validate($message);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], 400);
        }

        // Save product
        $entityManager->persist($message);
        $entityManager->flush();

        $message_json = [
            'text' => $message->getText(),
            'uuid' => $message->getUUID(),
        ];

        return new Response($message->getUUID());
    }

    #[Route('/message/{uuid}', name: 'message_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, string $uuid): Response
    {
        $message = $entityManager->getRepository(Message::class)->findByUuid($uuid);

        if (!$message) {
            throw $this->createNotFoundException(
                'No message found with uuid '.$uuid
            );
        }

        $message_json = [
            'text' => $message->getText(),
            'uuid' => $message->getUUID(),
        ];

        return new Response(json_encode($message_json),200);
    }

    #[Route('/message/{uuid}', name: 'message_edit', methods:['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, string $uuid): Response
    {
        $message = $entityManager->getRepository(Message::class)->findByUuid($uuid);

        if (!$message) {
            throw $this->createNotFoundException(
                'No message found with uuid '.$uuid
            );
        }

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['text'])) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }

        $message->setText($data['text']);
        $entityManager->flush();

        return new JsonResponse(null, 200);
    }
}
