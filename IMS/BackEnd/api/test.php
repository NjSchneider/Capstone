<?php

    include "connection.php";  
    include "dbFunctions.php";

    error_reporting(0);
    ini_set('display_errors', 0);
    
    // Global Variables
    $week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $sales = array();
    $games = array();
    $consoles = array();
    $equipment = array();
    $misc = array();
    $users = array();
    $product = array();
    $categories = [0, 0, 0, 0];
    $totalSales = 0;
    $action = $_GET['action'];    
    
    // Controller for Database Functions
    switch($action){
        case 'sell':
            $upc = $_GET['upc'];
            getProduct($upc);
        break;

        case 'ticket':
            createTicket();
        break;

        case 'users':
            switch($_GET['f']){
                case 'update':
                    updateUserInfo();
                break;
                
                case 'add':
                    addUser();
                break;
            }
        break;

        // Inventory Functions
        case 'inventory':
            switch($_GET['f']){
                case 'getall':
                    getAllProducts();
                break;

                case 'getconsoles':
                    getAllConsoles();
                break;

                case 'getgames':
                    getAllGames();
                break;

                case 'getgenerations':
                    getAllGenerations();
                break;

                case 'getbrand':
                    if(isset($_GET['console'])){
                        getConsoleBrand($_GET['console']);
                    }
                    if(isset($_GET['generation'])){
                        getGenerationBrand($_GET['generation']);
                    }
                break;

                case 'getbrands':
                    getAllBrands();
                break;

                case 'update':
                    editInventoryItem();
                break;
                
                case 'add':
                    addInventoryItem();
                break;

                // Inventory Search Functions
                case 'search':
                    switch($_GET['cat']){
                        case 'games':
                            getSearchGames();
                        break;

                        case 'consoles':
                            getSearchConsoles();
                        break;

                        case 'equipment':
                            getSearchEquipment();
                        break;

                        case 'specialty':
                            getSearchSpecialty();
                        break;
                    }
                break;
            }
        break;        

        // All functions for sales data
        case 'data':
            switch($_GET['f']){
                case 'games':
                    getGames();
                break;

                case 'gamesales':
                    getGames();
                    initSales();
                    getTopGames();
                break;

                case 'consoles':
                    getTopConsoles();
                break;

                case 'categories':
                    getTotalCategorySales();
                break;

                case 'employees':
                    getUserSales();
                break;

                case 'sales':
                    getTotalSales();
                break;

                case 'days':
                    initSales();
                    getTopOrderDays();
                break;

                case 'topgames':
                    getAllTimeGames();
                break;

                case 'topconsoles':
                    getAllTimeConsoles();
                break;

                case 'topequip':
                    getAllTimeEquipment();
                break;

                case 'topmisc':
                    getAllTimeMisc();
                break;
            }
        break;
    }  
?>

    