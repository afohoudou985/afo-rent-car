<?php
// database/seeders/TicketSeeder.php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Message;
use App\Models\User;
use App\Enums\TicketStatus;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TicketSeeder extends Seeder
{
    public function run(): void
    {

        $users = User::where('role', UserRole::CLIENT)->get();

        // Client Tickets (Authenticated Users)
        $this->createClientTickets($users);

        // Guest Tickets (Contact Form Submissions)
        $this->createGuestTickets();
    }

    private function createClientTickets($users)
    {
        // Ticket 1 : Problème de réservation - NOUVEAU
        $ticket1 = Ticket::create([
            'subject' => 'Impossible de finaliser la réservation pour BMW X5',
            'status' => TicketStatus::NEW,
            'user_id' => $users[0]->id,
        ]);

        Message::create([
            'ticket_id' => $ticket1->id,
            'message' => 'Bonjour, j’essaie de réserver une BMW X5 pour la semaine prochaine mais le paiement échoue à chaque fois. J’ai essayé plusieurs cartes bancaires qui fonctionnent pourtant sur d’autres sites. Pouvez-vous m’aider ?',
            'is_admin' => false,
        ]);

        Message::create([
            'ticket_id' => $ticket1->id,
            'message' => 'Bonjour ! Merci de nous avoir contactés. Désolé pour ce problème de paiement. Pouvez-vous indiquer approximativement l’heure à laquelle vous avez tenté la réservation ainsi que les 4 derniers chiffres de la carte utilisée ? Cela nous aidera à enquêter.',
            'is_admin' => true,
        ]);

        Message::create([
            'ticket_id' => $ticket1->id,
            'message' => 'J’ai essayé vers 15h aujourd’hui. La carte se terminant par 4567. J’ai aussi essayé une autre carte se terminant par 1234. Les deux ont échoué au moment du paiement.',
            'is_admin' => false,
        ]);

        // Ticket 2 : Signalement de dommage véhicule - EN COURS
        $ticket2 = Ticket::create([
            'subject' => 'Rayure mineure sur le véhicule retourné - Toyota Camry',
            'status' => TicketStatus::IN_PROGRESS,
            'user_id' => $users[1]->id,
        ]);

        Message::create([
            'ticket_id' => $ticket2->id,
            'message' => 'J’ai retourné mon véhicule de location (Toyota Camry, réservation #RC-2024-001) hier et il y avait une rayure mineure sur le pare-chocs arrière qui, selon moi, n’était pas présente lors de la prise en charge. Je crains d’être facturé pour un dommage que je n’ai pas causé.',
            'is_admin' => false,
        ]);

        Message::create([
            'ticket_id' => $ticket2->id,
            'message' => 'Merci de nous en avoir informés. J’ai retrouvé votre réservation et je suis en train de vérifier les photos d’inspection avant location. Je vais les comparer avec l’inspection de retour et revenir vers vous sous 24 heures.',
            'is_admin' => true,
        ]);

        Message::create([
            'ticket_id' => $ticket2->id,
            'message' => 'Merci d’examiner cela. J’apprécie votre réponse rapide.',
            'is_admin' => false,
        ]);

        // Ticket 3 : Problème d’accès compte - FERMÉ
        $ticket3 = Ticket::create([
            'subject' => 'Impossible d’accéder à mon compte après réinitialisation du mot de passe',
            'status' => TicketStatus::CLOSED,
            'user_id' => $users[2]->id,
            'resolved_at' => Carbon::now()->subDays(2),
        ]);

        Message::create([
            'ticket_id' => $ticket3->id,
            'message' => 'J’ai réinitialisé mon mot de passe mais je n’arrive toujours pas à me connecter. Le système indique que mes identifiants sont invalides.',
            'is_admin' => false,
        ]);

        Message::create([
            'ticket_id' => $ticket3->id,
            'message' => 'Je peux vous aider. Laissez-moi vérifier le statut de votre compte. Pouvez-vous confirmer l’adresse email associée à votre compte ?',
            'is_admin' => true,
        ]);

        Message::create([
            'ticket_id' => $ticket3->id,
            'message' => 'Oui, c’est ' . $users[2]->email,
            'is_admin' => false,
        ]);

        Message::create([
            'ticket_id' => $ticket3->id,
            'message' => 'J’ai identifié le problème. Votre compte a été temporairement verrouillé suite à plusieurs tentatives de connexion échouées. Je l’ai débloqué et vous ai envoyé un nouveau mot de passe temporaire. Veuillez vous reconnecter et changer votre mot de passe.',
            'is_admin' => true,
        ]);

        Message::create([
            'ticket_id' => $ticket3->id,
            'message' => 'Parfait ! J’ai pu me connecter avec succès. Merci pour votre aide rapide !',
            'is_admin' => false,
        ]);

        // Ticket 4 : Question de facturation - NOUVEAU
        $ticket4 = Ticket::create([
            'subject' => 'Frais inattendu sur ma carte bancaire',
            'status' => TicketStatus::NEW,
            'user_id' => $users[3]->id,
        ]);

        Message::create([
            'ticket_id' => $ticket4->id,
            'message' => 'Je vois un prélèvement de 75 $ sur ma carte bancaire que je ne reconnais pas. Il provient de votre société et date de la semaine dernière. Ma dernière location remonte à plus d’un mois. Pouvez-vous m’expliquer ce frais ?',
            'is_admin' => false,
        ]);

        // Ticket 5 : Demande de véhicule - EN COURS
        $ticket5 = Ticket::create([
            'subject' => 'Demande de véhicule spécial pour un mariage',
            'status' => TicketStatus::IN_PROGRESS,
            'user_id' => $users[4]->id,
        ]);

        Message::create([
            'ticket_id' => $ticket5->id,
            'message' => 'Bonjour ! Je me marie le mois prochain et je cherche une voiture de luxe pour le jour du mariage. Avez-vous des véhicules Bentley ou Rolls Royce disponibles ? Le mariage est prévu pour le 15 mars.',
            'is_admin' => false,
        ]);

        Message::create([
            'ticket_id' => $ticket5->id,
            'message' => 'Félicitations pour votre mariage ! Je vais vérifier la disponibilité de notre flotte de luxe pour le 15 mars. Nous avons quelques véhicules haut de gamme, mais je dois confirmer les modèles spécifiques. À quelle heure et pour quelle durée en auriez-vous besoin ?',
            'is_admin' => true,
        ]);

        Message::create([
            'ticket_id' => $ticket5->id,
            'message' => 'Merci ! J’en aurais besoin de 10h à environ 20h. La cérémonie est à 14h et la réception se termine vers 19h.',
            'is_admin' => false,
        ]);
    }

    private function createGuestTickets()
    {
        // Ticket invité 1 : Demande générale - NOUVEAU
        $guestTicket1 = Ticket::create([
            'subject' => 'Demande de réservation de groupe pour un événement d’entreprise',
            'status' => TicketStatus::NEW,
            'guest_name' => 'Sarah Johnson',
            'guest_email' => 'sarah.johnson@techcorp.com',
        ]);

        Message::create([
            'ticket_id' => $guestTicket1->id,
            'message' => 'Bonjour, j’organise un événement d’entreprise pour 50 participants et nous avons besoin de transport. Nous aurions besoin d’environ 10 à 12 véhicules pour un week-end en avril. Proposez-vous des réductions pour les groupes ? Quelle serait la meilleure façon d’organiser cela ?',
            'is_admin' => false,
        ]);

        // Ticket invité 2 : Question tarifaire - FERMÉ
        $guestTicket2 = Ticket::create([
            'subject' => 'Tarifs hebdomadaires pour location de SUV',
            'status' => TicketStatus::CLOSED,
            'guest_name' => 'Mike Rodriguez',
            'guest_email' => 'mike.r.email@gmail.com',
            'resolved_at' => Carbon::now()->subDays(1),
        ]);

        Message::create([
            'ticket_id' => $guestTicket2->id,
            'message' => 'Bonjour, je prévois des vacances en famille et j’ai besoin d’un SUV pour une semaine. Quels sont vos tarifs hebdomadaires et proposez-vous des réductions pour les locations d’une semaine ?',
            'is_admin' => false,
        ]);

        // Ticket invité 3 : Demande de localisation - NOUVEAU
        $guestTicket3 = Ticket::create([
            'subject' => 'Disponibilité de prise en charge à l’aéroport',
            'status' => TicketStatus::NEW,
            'guest_name' => 'Emily Chen',
            'guest_email' => 'emily.chen.traveler@outlook.com',
        ]);

        Message::create([
            'ticket_id' => $guestTicket3->id,
            'message' => 'Avez-vous un point de prise en charge à l’aéroport LAX ? J’arrive mardi prochain à 18h et j’aurai besoin d’un véhicule immédiatement après l’atterrissage. Quels documents dois-je apporter pour la location ?',
            'is_admin' => false,
        ]);

        // Ticket invité 4 : Réclamation - EN COURS
        $guestTicket4 = Ticket::create([
            'subject' => 'Mauvaise expérience avec le service client',
            'status' => TicketStatus::IN_PROGRESS,
            'guest_name' => 'Robert Thompson',
            'guest_email' => 'r.thompson.feedback@yahoo.com',
        ]);

        Message::create([
            'ticket_id' => $guestTicket4->id,
            'message' => 'J’ai essayé d’appeler votre service client trois fois hier et j’ai été mis en attente pendant plus de 30 minutes à chaque fois avant d’abandonner. J’ai une question simple concernant la prolongation de ma location actuelle mais je n’arrive à joindre personne. C’est très frustrant.',
            'is_admin' => false,
        ]);

        // Ticket invité 5 : Besoins spécifiques - NOUVEAU
        $guestTicket5 = Ticket::create([
            'subject' => 'Véhicule accessible en fauteuil roulant requis',
            'status' => TicketStatus::NEW,
            'guest_name' => 'Lisa Martinez',
            'guest_email' => 'lisa.martinez.family@gmail.com',
        ]);

        Message::create([
            'ticket_id' => $guestTicket5->id,
            'message' => 'Bonjour, j’ai besoin de louer un véhicule accessible en fauteuil roulant pour mon père qui en utilise un. Avez-vous ce type de véhicules ? Nous en avons besoin pour une semaine à partir du 20 février. Merci de m’indiquer les options disponibles ainsi que les tarifs.',
            'is_admin' => false,
        ]);
    }
}
