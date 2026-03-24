<?php

class TaskController
{
    private Task     $taskModel;
    private Category $categoryModel;

    public function __construct()
    {
        requireLogin();

        $this->taskModel     = new Task();
        $this->categoryModel = new Category();
    }

    public function index(): void
    {
        $userId   = $_SESSION['user_id'];
        $status   = $_GET['status']   ?? '';
        $priority = $_GET['priority'] ?? '';
        $sort     = $_GET['sort']     ?? 'deadline';

        $allowedStatuses    = ['', 'open', 'bezig', 'gedaan'];
        $allowedPriorities  = ['', 'laag', 'normaal', 'hoog'];

        if (!in_array($status, $allowedStatuses))   $status   = '';
        if (!in_array($priority, $allowedPriorities)) $priority = '';

        $tasks      = $this->taskModel->getAllByUser($userId, $status, $priority, $sort);
        $stats      = $this->taskModel->getStats($userId);
        $categories = $this->categoryModel->getAllByUser($userId);

        require APP . '/views/tasks/index.php';
    }

 
    public function create(): void
    {
        $categories = $this->categoryModel->getAllByUser($_SESSION['user_id']);
        require APP . '/views/tasks/form.php';
    }


    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('tasks');
        }

        validateCsrf();

        $errors = $this->validateTaskInput($_POST);

        if (!empty($errors)) {
            $categories = $this->categoryModel->getAllByUser($_SESSION['user_id']);
            $old        = $_POST; 
            require APP . '/views/tasks/form.php';
            return;
        }

        $created = $this->taskModel->create([
            'user_id'     => $_SESSION['user_id'],
            'category_id' => $_POST['category_id'] ?? null,
            'title'       => sanitize($_POST['title']),
            'description' => sanitize($_POST['description'] ?? ''),
            'priority'    => $_POST['priority'] ?? 'normaal',
            'status'      => $_POST['status'] ?? 'open',
            'deadline'    => $_POST['deadline'] ?? null,
        ]);

        if ($created) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Taak aangemaakt!'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Er ging iets mis. Probeer opnieuw.'];
        }

        redirect('tasks');
    }


    public function edit(int $id): void
    {
        $task = $this->taskModel->getById($id, $_SESSION['user_id']);

        if (!$task) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Taak niet gevonden.'];
            redirect('tasks');
        }

        $categories = $this->categoryModel->getAllByUser($_SESSION['user_id']);
        require APP . '/views/tasks/form.php';
    }


    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('tasks');
        }

        validateCsrf();

        $errors = $this->validateTaskInput($_POST);

        if (!empty($errors)) {
            $task       = $this->taskModel->getById($id, $_SESSION['user_id']);
            $categories = $this->categoryModel->getAllByUser($_SESSION['user_id']);
            $old        = $_POST;
            require APP . '/views/tasks/form.php';
            return;
        }

        $updated = $this->taskModel->update($id, $_SESSION['user_id'], [
            'category_id' => $_POST['category_id'] ?? null,
            'title'       => sanitize($_POST['title']),
            'description' => sanitize($_POST['description'] ?? ''),
            'priority'    => $_POST['priority'] ?? 'normaal',
            'status'      => $_POST['status'] ?? 'open',
            'deadline'    => $_POST['deadline'] ?? null,
        ]);

        $_SESSION['flash'] = $updated
            ? ['type' => 'success', 'msg' => 'Taak bijgewerkt!']
            : ['type' => 'error',   'msg' => 'Bijwerken mislukt.'];

        redirect('tasks');
    }


    public function delete(int $id): void
    {
        validateCsrf();

        $deleted = $this->taskModel->delete($id, $_SESSION['user_id']);

        $_SESSION['flash'] = $deleted
            ? ['type' => 'success', 'msg' => 'Taak verwijderd.']
            : ['type' => 'error',   'msg' => 'Verwijderen mislukt.'];

        redirect('tasks');
    }


    private function validateTaskInput(array $data): array
    {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Titel is verplicht.';
        } elseif (mb_strlen($data['title']) > 255) {
            $errors[] = 'Titel mag maximaal 255 tekens bevatten.';
        }

        $allowedPriorities = ['laag', 'normaal', 'hoog'];
        if (!in_array($data['priority'] ?? '', $allowedPriorities)) {
            $errors[] = 'Ongeldige prioriteit.';
        }

        $allowedStatuses = ['open', 'bezig', 'gedaan'];
        if (!in_array($data['status'] ?? '', $allowedStatuses)) {
            $errors[] = 'Ongeldige status.';
        }

        if (!empty($data['deadline'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['deadline']);
            if (!$date) {
                $errors[] = 'Ongeldige deadline datum.';
            }
        }

        return $errors;
    }
}
