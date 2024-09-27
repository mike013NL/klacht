<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Functie om foutmeldingen weer te geven
function showError($message)
{
    echo "<p style='color: red;'>$message</p>";
}

// Als het formulier verzonden is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $complaint = isset($_POST['complaint']) ? $_POST['complaint'] : '';

    // Controleer of de velden niet leeg zijn
    if (empty($name) || empty($email) || empty($complaint)) {
        showError('Alle velden zijn verplicht in te vullen.');
    } else {
        // Instantieer PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server instellingen
            $mail->isSMTP();
            $mail->Host = 'smtp.yourprovider.com';  // SMTP server van je provider
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@example.com'; // Je SMTP gebruikersnaam
            $mail->Password = 'your-password'; // Je SMTP wachtwoord
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Ontvangers
            $mail->setFrom('your-email@example.com', 'Klachtverwerking');
            $mail->addAddress($email); // Verstuur naar de gebruiker
            $mail->addCC('your-email@example.com'); // Zet jezelf in de CC

            // Inhoud van de e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Uw klacht is in behandeling';
            $mail->Body = "Beste $name,<br><br>Uw klacht is in behandeling.<br><br>"
                . "Gegevens:<br>"
                . "Naam: $name<br>"
                . "E-mail: $email<br>"
                . "Klacht: $complaint<br><br>"
                . "Met vriendelijke groet,<br>"
                . "Klantenservice";

            // Verstuur de e-mail
            if ($mail->send()) {
                echo '<p style="color: green;">Uw klacht is succesvol verzonden.</p>';
            } else {
                showError('Er is een probleem opgetreden bij het versturen van de e-mail.');
            }
        } catch (Exception $e) {
            showError("Er is een fout opgetreden: {$mail->ErrorInfo}");
        }
    }
}
?>

<!-- HTML Formulier -->
<form method="post" action="">
    <label for="name">Naam:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="complaint">Omschrijving van de klacht:</label><br>
    <textarea id="complaint" name="complaint" required></textarea><br><br>

    <input type="submit" value="Verzend klacht">
</form>
