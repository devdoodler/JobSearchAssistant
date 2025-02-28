<?php

namespace App\Controller;

use App\JobApplication\Domain\JobApplication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        new JobApplication(1);

        return $this->json('empty');
    }
}
