<?php

class SettingsController
{
    private UserSettings $settingsModel;

    public function __construct()
    {
        requireLogin();
        $this->settingsModel = new UserSettings();
    }

    public function index(): void
    {
        $settings = $this->settingsModel->getByUser($_SESSION['user_id']);
        require APP . '/views/settings/index.php';
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('settings');
        }

        validateCsrf();

        $updated = $this->settingsModel->update($_SESSION['user_id'], $_POST);

        $_SESSION['flash'] = $updated
            ? ['type' => 'success', 'msg' => 'Instellingen opgeslagen!']
            : ['type' => 'error',   'msg' => 'Opslaan mislukt.'];

        redirect('settings');
    }
}
