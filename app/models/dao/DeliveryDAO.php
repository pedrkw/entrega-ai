<?php

namespace App\Models\DAO;

use App\Functions\GenericDAO;
use App\Models\Delivery;
use App\Models\VehicleType;

class DeliveryDAO extends GenericDAO
{
    // Define o nome da tabela no banco de dados que esta classe irá manipular
    protected string $table = "deliveries";

    // Construtor da classe
    public function __construct()
    {
        // Chama o construtor da classe pai (GenericDAO) e passa a classe de entidade (Delivery::class)
        parent::__construct(Delivery::class);
    }

    // Pegar o todos os pedidos do usuário
    public function getDeliveries($user_id)
    { 
        $sql = "
        SELECT 
        deliveries.*, 
        vehicle_types.id AS vehicle_type_id, 
        vehicle_types.type_name AS vehicle_type_name, 
        vehicle_types.base_rate AS vehicle_base_rate, 
        vehicle_types.rate_per_km AS vehicle_rate_per_km,
        drivers.name AS driver_name,
        delivery_status.status_name AS delivery_status_name,
        delivery_status.status_description AS delivery_status_description,
        delivery_status.icon AS delivery_icon,
        delivery_status.css_class AS delivery_css_class
        FROM deliveries
        JOIN vehicle_types ON deliveries.vehicle_type_id = vehicle_types.id
        LEFT JOIN drivers ON deliveries.driver_id = drivers.id
        LEFT JOIN delivery_status ON deliveries.delivery_status_id = delivery_status.id
        WHERE deliveries.user_id = :user_id
        ORDER BY deliveries.created_at DESC;
        ";

        $params = [
            'user_id' => $user_id,
        ];

        return $this->executeQuery($sql, $params);
    }

    public function getTrackingDelivery($user_id, $delivery_id)
    {
        $sql = "
            SELECT 
                deliveries.*, 
                vehicle_types.id AS vehicle_type_id, 
                vehicle_types.type_name AS vehicle_type_name, 
                vehicle_types.base_rate AS vehicle_base_rate, 
                vehicle_types.rate_per_km AS vehicle_rate_per_km,
                drivers.name AS driver_name,
                delivery_status.status_name AS delivery_status_name,
                delivery_status.status_description AS delivery_status_description,
                delivery_status.icon AS delivery_icon,
                delivery_status.css_class AS delivery_css_class
            FROM deliveries
            JOIN vehicle_types ON deliveries.vehicle_type_id = vehicle_types.id
            LEFT JOIN drivers ON deliveries.driver_id = drivers.id
            LEFT JOIN delivery_status ON deliveries.delivery_status_id = delivery_status.id
            WHERE deliveries.user_id = :user_id AND deliveries.id = :delivery_id
            ORDER BY deliveries.created_at DESC;
        ";

        $params = [
            'user_id' => $user_id,
            'delivery_id' => $delivery_id,
        ];

        return $this->executeQuery($sql, $params);
    }

   


    

}
