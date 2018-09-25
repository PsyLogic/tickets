<?php

use Illuminate\Database\Seeder;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tickets')
            ->delete();
        $ticket = new \App\Ticket();
        $ticket->subject = 'ticket1';
        $ticket->description = ' Sapiente minima voluptas id, in tempora aut voluptatem.';
        $ticket->type = 'opened';
        $ticket->priority_id = 1;
        $ticket->owner_id = 1;
        $ticket->agent_id = 6;
        $ticket->status_id = 1;
        $ticket->category_id = 1;
        $ticket->save();

        $ticket = new \App\Ticket();
        $ticket->subject = 'ticket2';
        $ticket->description = '  A dampata Sapiente minima voluptas id, in tempora aut voluptatem.';
        $ticket->type = 'opened';
        $ticket->priority_id = 2;
        $ticket->owner_id = 2;
        $ticket->agent_id = 7;
        $ticket->status_id = 2;
        $ticket->category_id = 2;
        $ticket->save();

        $ticket = new \App\Ticket();
        $ticket->subject = 'ticket3';
        $ticket->description = '  A dampata Sapiente minima voluptas id, in tempora aut voluptatem.';
        $ticket->type = 'closed';
        $ticket->priority_id = 3;
        $ticket->owner_id = 3;
        $ticket->agent_id = 7;
        $ticket->status_id = 3;
        $ticket->category_id = 3;
        $ticket->save();
    }
}
