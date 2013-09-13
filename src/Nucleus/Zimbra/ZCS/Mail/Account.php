<?php
/**
 * Mail class to query the ZCS api for Mail and Calendar related requests.
 * Mostly a copy of the Admin class.
 *
 * @author Reinier Pelayo
 */
namespace Zimbra\ZCS\Mail;

class Account extends \Zimbra\ZCS\Mail
{
    /**
     * Returns the iCal vobject for the current user.
     *
     * @return SimpleXML object calendar
     */
    public function getCal()
    {
        $attributes = array();

        $params = array();

        $response = $this->soapClient->request('GetICalRequest', $attributes, $params);
        $calList = $response->children()->SearchDirectoryResponse->children();

        return $calList;
    }

    /**
     * Get all appointments
     *
     * @return SimpleXML object calendar
     */
    public function getAppointments($start,$end)
    {
        $attributes = array(
            'calExpandInstStart' => $start,
            'calExpandInstEnd' => $end,
            'types' => 'appointment'
            );

        $params = array(
            'query' => 'Item:all'
            );

        $response = $this->soapClient->request('SearchRequest', $attributes, $params);
        $appointments = $response->children()->SearchResponse;
        return $appointments;

    }

    public function getAppointmentsByUserID($userID, $searchByName){
        $attributes = array(
            'calExpandInstStart' =>   1377925200000,//"1377009106000",//date("Ymd") ."T010000Z"   ,//
            'calExpandInstEnd' =>     1377925200000,//"0",//date("Ymd") ."T010000Z",
            'types' => 'appointment'
        );

        $params = array(
            'query' => "inid:\"$userID\" #name:\"$searchByName\""
            //Item:all
            //#loc:\"Rhoden's Office\"
            //#recur:no
            //#name:\"scrum\"
        );
        $response = $this->soapClient->request('SearchRequest', $attributes, $params);
        $appointments = $response->children()->SearchResponse;
        return $appointments;
    }

    public function getRecurByAppointmentID($id){
        $attributes = array(
            //'calExpandInstStart' =>   1377925200000,//"1377009106000",//date("Ymd") ."T010000Z"   ,//
            //'calExpandInstEnd' =>     1377925200000,//"0",//date("Ymd") ."T010000Z",
            //'types' => 'appointment'
            'id' => $id
        );
        $params = array(

        );
        $response = $this->soapClient->request('GetRecurRequest', $attributes, $params);
        $GetRecurResponse = $response->children()->GetRecurResponse;
        return $GetRecurResponse;

    }


    /**
     * Get appointment by uid
     *
     * @return SimpleXML object calendar
     */
    public function getAppointment($uid)
    {
        $attributes = array(
            'uid' => $uid,
            'includeContent' => '1'
            );

        $params = array();

        $response = $this->soapClient->request('GetAppointmentRequest', $attributes, $params);
        $appointment = $response->children()->GetAppointmentResponse->children();

        return $appointment;
    }

    /**
     * Creates an appointment.
     *
     * @param string $uid The UID of the appointment to cancel.
     * @return simpleXML vobject with the cancelled appointment.
     */
    public function createAppointment($start, $end, $subject, $description, $location)
    {
        $attributes = array();
        $params = array(
            'm' => array(
                'inv' => array(
                    'comp' => array(
                        'at' => array(
                            'role'  => "NON",
                            'ptst'  => "NE",
                            'cutype'=> "RES",
                            'rsvp'  => "0",
                            'a' => "$location@franklinamerican.com",
                            'd' => "6th Floor Dev Conference Room"
                        ),
                        'attributes' => array(
                            'status' => 'CONF',
                            'fb' => 'B',
                            'name' => $subject,
                            'loc' => $location
                        ),
                        's' => array(
                            'attributes' => array(
                                'd' => $start
                            )
                        ),
                        'e' => array(
                            'attributes' => array(
                                'd' => $end
                            )
                        ),
                        'or'=>array(
                            "a"=>"voyager@franklinamerican.com",
                            "d"=>"voy ager"
                        ),
                        "allDay"=>"0",
                        'descHtml' => $description,
                        'desc' => $subject,
                        'alarm' => array(
                            'attributes' => array(
                                'action' => 'DISPLAY'
                            ),
                            'trigger' => array(
                                'rel' => array(
                                    'attributes' => array(
                                        'm' => 1
                                    )
                                )
                            )
                        )
                    )
                ),
                'e' => array(
                    'a' => "$location@franklinamerican.com",
                    'p' => "6th Floor Dev Conference Room",
                    't' => "t"
                )
            )
        );
        $response = $this->soapClient->request('CreateAppointmentRequest', $attributes, $params);

        return $response;
    }

    /**
     * Deletes an appointment instance.
     *
     * @param string $id The id of the appointment to cancel.
     * @return simpleXML vobject with the cancelled appointment.
     */
    public function cancelAppointment($id, $cancelDate)
    {
        $attributes = array(
            'id' => $id,
            'comp' => '0'
            );
        $params = array(
            'inst' => array(
                'd' => $cancelDate
                )
            );
        $response = $this->soapClient->request('CancelAppointmentRequest', $attributes, $params);
        return $response;
    }

     /**
     * Deletes an appointment series.
     *
     * @param string $id The id of the appointment to cancel.
     * @param string $cancelDate The start date to cancel.
     * @return simpleXML vobject with the cancelled appointment.
     */
    public function deleteAppointmentSeries($id)
    {
        $attributes = array(
            'id' => $id,
            'comp' => '0'
            );

        $params = array();

        $response = $this->soapClient->request('CancelAppointmentRequest', $attributes, $params);

        return $response;
    }
}
