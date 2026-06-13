<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomService
{
    protected $client;
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->accountId = config('zoom.account_id');
        $this->clientId = config('zoom.client_id');
        $this->clientSecret = config('zoom.client_secret');

        $this->authenticate();
    }

    // Authenticate with Zoom API
    protected function authenticate()
    {
        $response = Http::post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $this->accountId,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->successful()) {
            $this->accessToken = $response->json()['access_token'];
            $this->client = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ]);
        }
    }

    // Get headers for API request
    protected function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ];
    }

    // Create a meeting
    public function createMeeting($data)
    {
        $response = $this->client->post('https://api.zoom.us/v2/users/me/meetings', [
            'json' => $data
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Zoom API Error: ' . $response->body());
    }

    // Update a meeting
    public function updateMeeting($meetingId, $data)
    {
        $response = $this->client->patch("https://api.zoom.us/v2/meetings/{$meetingId}", [
            'json' => $data
        ]);

        if (!$response->successful()) {
            throw new \Exception('Zoom API Error: ' . $response->body());
        }

        return true;
    }

    // Delete a meeting
    public function deleteMeeting($meetingId)
    {
        $response = $this->client->delete("https://api.zoom.us/v2/meetings/{$meetingId}");

        if (!$response->successful()) {
            throw new \Exception('Zoom API Error: ' . $response->body());
        }

        return true;
    }

    // Get meeting details
    public function getMeeting($meetingId)
    {
        $response = $this->client->get("https://api.zoom.us/v2/meetings/{$meetingId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Zoom API Error: ' . $response->body());
    }

    // *********** এখানে নতুন মেথড যোগ করুন ***********

    // Get meeting recordings (NEW METHOD)
    public function getMeetingRecordings($meetingId)
    {
        $response = $this->client->get("https://api.zoom.us/v2/meetings/{$meetingId}/recordings");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Zoom API Error: ' . $response->body());
    }

    // End a meeting (NEW METHOD)
    public function endMeeting($meetingId)
    {
        $response = $this->client->put("https://api.zoom.us/v2/meetings/{$meetingId}/status", [
            'json' => ['action' => 'end']
        ]);

        return $response->successful();
    }

    // List all meetings (NEW METHOD)
    public function listMeetings($userId = 'me', $from = null, $to = null)
    {
        $params = [];
        if ($from) $params['from'] = $from;
        if ($to) $params['to'] = $to;

        $response = $this->client->get("https://api.zoom.us/v2/users/{$userId}/meetings", [
            'query' => $params
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Zoom API Error: ' . $response->body());
    }

    // Get meeting participants (NEW METHOD)
    public function getMeetingParticipants($meetingId)
    {
        $response = $this->client->get("https://api.zoom.us/v2/report/meetings/{$meetingId}/participants");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Zoom API Error: ' . $response->body());
    }
}
