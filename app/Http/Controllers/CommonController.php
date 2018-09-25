<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Mail;
use Illuminate\Support\Collection;
use Swift_TransportException;

class CommonController extends Controller
{
	public static function getDate($month){

		$dateArray = [];
		$dateArray['backStartDate']  = Carbon::now()->startOfMonth()->modify(-$month."months");
		$dateArray['backEndDate']  =  Carbon::now()->startOfMonth()->modify(-$month."months")->endOfMonth();
		return $dateArray;
	}

	public static function prepareAndSendEmail($email_id,$emailInfo, $fieldValues, $throw = true)
	{
		try {
			$template  = EmailTemplate::getEmailTemplate($email_id);
			$emailText = $template->content;

			foreach ($fieldValues as $key => $value) {
				$emailText            = str_replace('##' . $key . '##', $value, $emailText);
			}

			try {
				Mail::send('emails.welcome', ['body' => $emailText], function ($message) use ($fieldValues,$emailInfo, $throw) {
					$message->from( env('MAIL_FROM'),env('MAIL_FROM_NAME'));
					$message->to($emailInfo['to'],$emailInfo['name'])->subject($fieldValues['TICKETNAME']);
				});
			}
			catch (Exception $e) {
				Log::error("Error sending mail: " . $e->getMessage() . "\nData: " . json_encode($emailInfo));
				if ($throw) {
					return 'Mail not sent Due to Connection Problem';
				}
			}
		}
		catch (Exception $e) {
			Log::error("Error sending mail: " . $e->getMessage());
			if ($throw) {
				return 'Mail not sent due to some problem';
			}
		}
	}
}
