<?php 
namespace App\Controller;

use App\Form\EmailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(Request $request, MailerInterface $mailer): Response
    {
        // Create the form
        $form = $this->createForm(EmailType::class);

        // Handle the form submission
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Create the email
            $email = (new Email())
                ->from('your_email@example.com')
                ->to($data['recipient'])
                ->subject($data['subject'])
                ->text($data['message']);

            // Send the email
            $mailer->send($email);

            // Add a flash message for success feedback
            $this->addFlash('success', 'Email sent successfully!');

            // Redirect to the same route to show the form again
            return $this->redirectToRoute('send_email');
        }

        // Render the form in the template
        return $this->render('email/send_email.html.twig', [
            'email_form' => $form->createView(),
        ]);
    }
}
