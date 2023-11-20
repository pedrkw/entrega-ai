<?php

namespace App\Controllers;

use App\Models\Address;
use App\Models\DAO\AddressDAO;
use App\Models\Delivery;
use App\Models\DAO\DeliveryDAO;
use App\Models\VehicleType;
use App\Models\DAO\VehicleTypeDAO;
use App\Functions\Database;
use App\Functions\View;
use App\Functions\Message;
use App\Functions\Layout;
use App\Functions\Helpers;

class DeliveryController
{   

    public function renderHistoric()
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

        $user_id = (int) $_SESSION['user_id'];

        $deliveryDAO = new DeliveryDAO();

        $deliveries = $deliveryDAO->getDeliveries($user_id);

        $title = "Meu histórico | Entrega aí";
        $data = [
            'title' => $title,
            'deliveries' => $deliveries,
            'menuDinamic' => '/historico'
        ];

        View::render('users/historic', $data, 'default');
    }


    public function trackingDelivery()
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

        $title = "Rastrear encomenda | Entrega aí";
        $data = [
            'title' => $title,
            'menuDinamic' => '/rastrear'
        ];

        View::render('users/tracking', $data, 'default');
    }

    public function trackingDeliveryId($delivery_id)
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

        if(!isset($delivery_id)){
            Helpers::redirect('usuario/historico');   
        }

        $user_id = (int) $_SESSION['user_id'];

        $deliveryDAO = new DeliveryDAO();

        $delivery = $deliveryDAO->getByDeliveryId($delivery_id);

        $title = "Rastrear encomenda #". $delivery_id ." | Entrega aí";
        $data = [
            'title' => $title,
            'delivery' => $delivery,
            'delivery_id' => $delivery_id,
            'menuDinamic' => '/rastrear'
        ];

        View::render('users/tracking', $data, 'default');
    }

    // Cadastro de novo pedido
    public function newDelivery()
    {   
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Helpers::redirect('usuario/servicos');
        }

        $user_id = $_SESSION['user_id'];

        if($_POST['user_id'] != $user_id){
            $_SESSION['msg'] = Message::error("Ocorreu um erro! Tente novamente!");
            Helpers::redirect('usuario/servicos');
        }


        // Instância e atribuição dos atributos
        $delivery = new Delivery();

        $delivery->setDelivery_id(Helpers::generateUniqueRandomString());
        $delivery->setUser_id($user_id);
        
        $delivery->setSender_latitude($_POST['sender_latitude']);
        $delivery->setSender_longitude($_POST['sender_longitude']);
        $delivery->setSender_address_details($_POST['sender_address_details']);
        $delivery->setSender_house_number($_POST['sender_house_number']);

        $delivery->setRecipient_name($_POST['recipient_name']);
        $delivery->setRecipient_latitude($_POST['recipient_latitude']);
        $delivery->setRecipient_longitude($_POST['recipient_longitude']);
        $delivery->setRecipient_address_details($_POST['recipient_address_details']);
        $delivery->setRecipient_house_number($_POST['recipient_house_number']);
        
        $delivery->setVehicle_type_id($_POST['vehicle_type_id']);
        $delivery->setWeight($_POST['weight']);
        $delivery->setTotal_km($_POST['total_km']);
        $delivery->setTotal_price($_POST['total_price']);
        $delivery->setDelivery_details($_POST['delivery_details']);

        $deliveryDAO = new DeliveryDAO();

        $insertedDeliveryId = $deliveryDAO->insert($delivery->toArrayGet());

        if ($insertedDeliveryId > 0) {
            $_SESSION['msg'] = Message::success("Serviço solicitado! Em breve um motorista parceiro irá buscar sua encomenda!");
            Helpers::redirect('usuario/historico');

        } else {

            $_SESSION['msg'] = Message::error("Erro ao solicitar serviço. Tente novamente!");
            Helpers::redirect('usuario/servicos');

        }
    }

}
