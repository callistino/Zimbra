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
    public function getAppointments()
    {
        $attributes = array(
            'calExpandInstStart' => "0",
            'calExpandInstEnd' => "0",
            'types' => 'appointment'
            );

        $params = array(
            'query' => 'Item:all'
            );

        $response = $this->soapClient->request('SearchRequest', $attributes, $params);
        $appointments = $response->children()->SearchResponse;

        return $appointments;
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
