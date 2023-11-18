<?php

namespace App\Controllers;

use App\Models\VehicleType;
use App\Models\DAO\VehicleTypeDAO;
use App\Functions\Database;
use App\Functions\View;
use App\Functions\Message;
use App\Functions\Layout;
use App\Functions\Helpers;

class ServiceController
{   

    public function renderService()
    {   
        // Verificar se o usuário já está autenticado
        if (!isset($_SESSION['user_id'])) 
        {
            Helpers::redirect('usuario/login');
        }

        // Verificar se o motorista já está autenticado
        if (isset($_SESSION['driver_id'])) 
        {
            Helpers::redirect('motorista/dashboard');
        }
        
        $vehicleTypeDAO = new VehicleTypeDAO();
        $vehicleTypes = $vehicleTypeDAO->getAll();

        $title = "Solicite um serviço | Entrega aí";
        $data = [
            'title' => $title,
            'vehicleTypes' => $vehicleTypes,
            'menuDinamic' => '/servicos'
        ];

        View::render('users/service', $data, 'default');
        
    }

}
