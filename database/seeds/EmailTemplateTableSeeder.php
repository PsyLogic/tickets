<?php

use Illuminate\Database\Seeder;

class EmailTemplateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')
            ->delete();
        $ticket = new \App\EmailTemplate();
        $ticket->id = 'close';
        $ticket->subject = 'Ticket Closed';
        $ticket->content = 'Hello <span style="font-weight: bold;">##NAME## </span><br><br>Your ticket<strong> ##TICKETNAME##</strong> has been closed';
        $ticket->save();

        $ticket = new \App\EmailTemplate();
        $ticket->id = 'create';
        $ticket->subject = 'Ticket Created';
        $ticket->content = 'Hello <span style="font-weight: bold;">##NAME## </span><br><br>Your ticket<strong> ##TICKETNAME##</strong> is created';
        $ticket->save();

        $ticket = new \App\EmailTemplate();
        $ticket->id = 'delete';
        $ticket->subject = 'Ticket Deleted';
        $ticket->content = 'Hello <span style="font-weight: bold;">##NAME## </span><br><br>Your ticket<strong> ##TICKETNAME##</strong> is  deleted';
        $ticket->save();

        $ticket = new \App\EmailTemplate();
        $ticket->id = 'reopen';
        $ticket->subject = 'Ticket ReOpened';
        $ticket->content = 'Hello mr.<span style="font-weight: bold;">##NAME## </span><br><br>Your ticket<strong> ##TICKETNAME##</strong> is reopened';
        $ticket->save();

        $ticket = new \App\EmailTemplate();
        $ticket->id = 'update';
        $ticket->subject = 'Ticket Updated';
        $ticket->content = 'Hello <span style="font-weight: bold;">##NAME## </span><br><br>Your ticket<strong> ##TICKETNAME##</strong>were updated';
        $ticket->save();



    }
}
