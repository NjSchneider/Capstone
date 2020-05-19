<?php
include "connection.php";
//connect is the dns

//Pass it a table name and return all records
function GetAll($stableName){
    global $pdo;
    $sql = "SELECT * from $stableName";
    $pdostate = $pdo->query($sql);
    $array = $pdostate->fetchAll();
    return $array;
}
//*******************Sets the size of the $games global array*******************
function initSales(){
    global $week;
    global $games;
    global $sales;

    switch($_GET['f']){
        case 'days':
            for($i = 0; $i < sizeof($week); $i++){
                array_push($sales, 0);
            }
        break;

        case 'gamesales':
            for($i = 0; $i < 5; $i++){
                array_push($sales, 0);
            }
        break;
    }
}
//*******************Only use for table that have used*******************
function GetAllUsed($stableName){
    global $pdo;
    $sql = "SELECT * from $stableName WHERE Used = 1";
    $pdostate = $pdo->query($sql);
    $array = $pdostate->fetchAll();
    return $array;
}
function GetAllNew($stableName){
    global $pdo;
    $sql = "SELECT * from $stableName WHERE Used =0";
    $pdostate = $pdo->query($sql);
    $array = $pdostate->fetchAll();
    return $array;
}
//*************************************************************************
function Delete($stableName,$sID,$NameID){
    global $pdo;
    $sqlDelete = "DELETE FROM $stableName WHERE $NameID = $sID";
    $pdos = $pdo->query($sqlDelete);
}

//Products******************************************
function getAllProducts(){
    global $pdo;
    $inventory = array();
    $sql = "SELECT * FROM products";
    $query = $pdo->prepare($sql);        
    $query->execute();

    while($row = $query->fetchAll(PDO::FETCH_ASSOC)){
        $inventory = $row;
    }

    $json = json_encode($inventory);
    echo $json;
}
// Gets Product from UPC
function getProduct($upc){   
    global $pdo;

    $sql = "SELECT * FROM products WHERE productID = '$upc'";
    $result = $pdo->query($sql);
    
    if($_GET['action'] == 'sell'){
        $json_array = array();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            $json_array[] = $row;
        }
        $json = json_encode($json_array);
        echo $json;
    }
    else{
        $result->execute();
        return $result->fetch();
    }
}
// Gets Product's price from UPC
function getProductPrice($product){
    global $pdo;
    global $totalSales;
    $sql = "SELECT price FROM products WHERE productID = '$product'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $itemPrice = $query->fetch();
    $totalSales += $itemPrice['price'];
}

//Customers******************************************
function GetCustomerByCustID($sID){
    global $pdo;
    $sql ="SELECT * FROM customer WHERE customerID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();
}

//Brands*************************************
// Returns all Brands
function GetAllBrands(){
    global $pdo;
    $sql ="SELECT * FROM brands";
    $query = $pdo->query($sql);
    $query->execute();
    $brands = $query->fetchAll();  
    
    $json = json_encode($brands);
    echo $json;
}
// Returns Brand from ID
function GetBrandByID($sID){
    global $pdo;
    $sql ="SELECT * FROM brands WHERE brandID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();
}
// Returns Brand based on Name given
function GetBrandByName($sName){
    global $pdo;
    $sql ="SELECT * FROM brands WHERE name like $sName";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();
}
// Returns BrandID baswd on the given Brand name
function getBrandID($brand){
    global $pdo;
    $sql = "SELECT brandID from brands WHERE name = '$brand'";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetch();
}

//Consoles*****************************************
// Returns all Consoles
function GetAllConsoles(){
    global $pdo;
    $sql ="SELECT * FROM consoles";
    $query = $pdo->query($sql);
    $query->execute();
    $consoles = $query->fetchAll();  
    
    $json = json_encode($consoles);
    echo $json;
}
// Returns BrandID based on Console name
function getConsoleBrand($sName){
    global $pdo;

    $sql = "SELECT brandID from consoles WHERE name = '$sName'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $result = $query->fetch();
    $brand = $result['brandID'];

    $result = GetBrandByID($brand);    
    $json = json_encode($result['name']);
    echo $json;

}
// Returns GenerationID based on Console name
function getConsoleID($console){
    global $pdo;
    $sql = "SELECT generationID FROM consoles WHERE name = '$console'";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetch();
}
// Returns Console info based on ID
function GetConsoles($sID){
    global $pdo;
    $sql ="SELECT * FROM consoles WHERE consoleID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();

}
// Returns all consoleIDs
function getAllConsoleIDs(){
    global $pdo;

    $sql = "SELECT consoleID FROM consoles";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}
// Returns Consoles based on brandID
function GetConsolesByBrandID($sID){
    global $pdo;
    $sql ="SELECT * FROM consoles WHERE brandID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns Consoles based on generationID
function GetConsolesByGenID($sID){
    global $pdo;
    $sql ="SELECT * FROM consoles WHERE generationID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns Consoles based on name
function GetConsolesByName($sName){
    global $pdo;
    $sql ="SELECT * FROM consoles WHERE name like $sName";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns consoleID & name based on consoleID
function getAllSoldConsoles($id){
    global $pdo;

    $sql = "SELECT consoleID, name FROM consoles WHERE consoleID = $id";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}
// Returns Returns top selling Consoles in JSON format
function getTopConsoles(){
    global $pdo;
    global $consoles;
    global $product;

    $tickets = getAllTicketIDs();
    foreach($tickets as $ticket){
        getConsoleSales($ticket['ticketID']);
    }     

    $json = json_encode($consoles);
    echo $json;
}
// Logic for top Console Sales
function getConsoleSales($ticket){
    global $pdo;
    global $consoles;

    $order = getTicketItemInfo($ticket);

    foreach($order as $item){
        $result = getAllSoldConsoles($item['productID']);
        
        foreach($result as $console){
            if(sizeOf($consoles) > 0){
                for($i = 0; $i < sizeOf($consoles); $i++){
                    if($console['consoleID'] == $consoles[$i]->upc){
                        $consoles[$i]->sales += 1;
                        break;
                    }
                    else if($i == sizeof($consoles) - 1){      
                        array_push($consoles, (object)["upc"=>$console['consoleID'], "name"=>$console['name'], "sales"=>1]);
                        break;
                    }
                }
            }
            else{
                array_push($consoles, (object)["upc"=>$console['consoleID'], "name"=>$console['name'], "sales"=>1]);
            }
        }            
    }        
}
// Add Console to Console Table
function addConsole($product){
    global $pdo;

    $result = getGenerationID($product->generation);
    $generation = $result['generationID'];
    $brand = getGenerationBrand($product->generation);
    $id = $product->id;
    $name = $product->name;

    $sql = "INSERT INTO consoles VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $id);
    $query->bindValue(2, $name);
    $query->bindValue(3, $generation);
    $query->bindValue(4, $brand);

    if($query->execute()){
        echo "Console Added";
    }
    else{
        echo "Failed";
    }
}

//Equipment***********************************
// Returns all equipmentIDs
function getAllEquipmentIDs(){
    global $pdo;

    $sql = "SELECT equipmentID FROM equipment";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}
// Returns Equipment based on equipmentID
function GetEquipmentByID($sID){
    global $pdo;
    $sql ="SELECT * FROM equipment WHERE equipmentID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();
}
// Returns Equipment based on consoleID
function GetEquipmentByConsoleID($sID){
    global $pdo;
    $sql ="SELECT * FROM equipment WHERE consoleID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Equipment based on Type
function GetEquipmentByType($sType){
    global $pdo;
    $sql ="SELECT * FROM equipment WHERE type ='$sType'";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Equipment based on name
function GetEquipmentByName($sName){
    global $pdo;
    $sql ="SELECT * FROM equipmenttype WHERE name like$sName";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Returns top selling Equipment in JSON format
function getAllSoldEquipment($id){
    global $pdo;

    $sql = "SELECT equipmentID, name FROM equipment WHERE equipmentID = $id";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}
// Returns all time selling equipment
function getAllTimeEquipment(){
    global $pdo;
    global $equipment;

    $tickets = getAllTicketIDs();
    foreach($tickets as $ticket){
        getEquipmentSales($ticket['ticketID']);
    }  

    $json = json_encode($equipment);
    echo $json;
}
// Equipment Sales logic
function getEquipmentSales($ticket){
    global $pdo;
    global $equipment;
    
    $order = getTicketItemInfo($ticket);

    foreach($order as $item){
        $equip = getAllSoldEquipment($item['productID']);            
        if($equip){
            if(sizeOf($equipment) > 0){
                for($i = 0; $i < sizeOf($equipment); $i++){    
                    if($equip[0]['equipmentID'] == $equipment[$i]->upc){
                        $equipment[$i]->sales += 1;
                        break;
                    }
                    else if($i == sizeof($equipment) - 1){      
                        array_push($equipment, (object)["upc"=>$equip[0]['equipmentID'], "name"=>$equip[0]['name'], "sales"=>1]);
                        break;
                    }
                }
            }
            else{
                array_push($equipment, (object)["upc"=>$equip[0]['equipmentID'], "name"=>$equip[0]['name'], "sales"=>1]);
            }
        } 
    }          
}
// Adds Product to Equipment Table
function addEquipment($product){
    global $pdo;

    $id = $product->id;
    $result = getConsoleID($product->console);
    $console = $result['generationID'];
    $name = $product->name;
    $type = $product->genre;

    $sql = "INSERT INTO equipment VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $id);
    $query->bindValue(2, $console);
    $query->bindValue(3, $name);
    $query->bindValue(4, $type);

    $query->execute();
}

//Games***********************************
// Returns Games based on gameID
function GetGamesByGameID($sID){
    global $pdo;
    $sql ="SELECT * FROM games WHERE gameID =$sID";
    $pdostate = $pdo->prepare($sql);
    return $pdostate->fetch();
}
// Returns Games based on consoleID
function GetGamesByConsoleID($sID){
    global $pdo;
    $sql ="SELECT * FROM games WHERE consoleID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Games based on name
function GetGamesByName($sName){
    global $pdo;
    $sql ="SELECT * FROM games WHERE name like $sName";
    $pdostate = $pdo->prepare($sql);
    $pdostate->execute();
    return $pdostate->fetchAll();

}
// Returns Games based on releaseDate
function GetGamesByDate($sDate){
    global $pdo;
    $sql ="SELECT * FROM games WHERE releaseDate like $sDate";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Games based on genre
function GetGamesByGenre($sGenre){
    global $pdo;
    $sql ="SELECT * FROM games WHERE genre like $sGenre";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Games based on gameID
function GetGamesByBrandID($sID){
    global $pdo;
    $sql ="SELECT * FROM games WHERE gameID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Game sales based on date range given 
function getGames(){
    global $pdo;
    global $games;

    $currentDay = date('Y-m-d');
    if($_GET['range'] == 'Weekly'){
        $range = date("Y-m-d", strtotime("previous monday"));          
    }
    else if($_GET['range'] == 'Monthly'){
        $range = date('Y-m').'-01';  
    }
    else{
        $range = date('Y').'-01-01';
    }

    $sql = "SELECT games.name, COUNT(productID) AS sales FROM games
            JOIN ticketitems on games.gameID = ticketitems.productID
            JOIN tickets on tickets.ticketID = ticketItems.ticketID
            WHERE tickets.orderDate BETWEEN '$range' AND '$currentDay'
            GROUP BY games.name
            ORDER BY sales DESC";

    $query = $pdo->prepare($sql);
    $query->execute();

    $result = $query->fetchAll();
    for($i = 0; $i < sizeof($result); $i++){
        $games[$i] = (object) array( name=>$result[$i]['name'], sales=>$result[$i]['sales']);
    }

    if($_GET['f'] == 'games'){
        $json = json_encode($games);
        echo $json;
    }
}
// Returns all GameIDs 
function getAllGameIDs(){
    global $pdo;

    $sql = "SELECT gameID FROM games";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}
// Returns all sold games
function getTopGames(){
    global $pdo;
    global $sales;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getTicketItems($ticket['ticketID']);
    }

    $json = json_encode($sales);    
    echo $json; 
}
// Returns gameID & name based on gameID
function getAllSoldGames($id){
    global $pdo;

    $sql = "SELECT gameID, name FROM games WHERE gameID = $id";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}
// Returns all time selling games
function getAllTimeGames(){
    global $pdo;
    global $games;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getGameSales($ticket['ticketID']);
    }     

    $json = json_encode($games);

    echo $json;
}
// Game Sales Logic
function getGameSales($ticket){
    global $pdo;
    global $games;
    
    $order = getTicketItemInfo($ticket);

    foreach($order as $item){
        $game = getAllSoldGames($item['productID']);            
        if($game){
            if(sizeOf($games) > 0){
                for($i = 0; $i < sizeOf($games); $i++){    
                    if($game[0]['gameID'] == $games[$i]->upc){
                        $games[$i]->sales += 1;
                        break;
                    }
                    else if($i == sizeof($games) - 1){      
                        array_push($games, (object)["upc"=>$game[0]['gameID'], "name"=>$game[0]['name'], "sales"=>1]);
                        break;
                    }
                }
            }
            else{
                array_push($games, (object)["upc"=>$game[0]['gameID'], "name"=>$game[0]['name'], "sales"=>1]);
            }
        } 
    }          
}
// Returns all Games
function GetAllGames(){
    global $pdo;
    $sql ="SELECT * FROM games";
    $query = $pdo->query($sql);
    $query->execute();
    $games = $query->fetchAll();  
    
    if($_GET['f'] == 'getgames'){
        $json = json_encode($games);
        echo $json;
    }
    else{
        $result = array();
        foreach($games as $game){
            array_push($result, $game['gameID']);
        }
        return $result;
    }
}
// Adds product to Games table
function addGame($product){
    global $pdo;

    $result = getConsoleID($product->console);
    $console = $result['generationID'];
    $date = date("Y-m-d");
    $id = $product->id;
    $name = $product->name;
    $genre = $product->genre;

    $sql = "INSERT INTO games VALUES(?, ?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $id);
    $query->bindValue(2, $console);
    $query->bindValue(3, $name);
    $query->bindValue(4, $date);
    $query->bindValue(5, $genre);

    if($query->execute()){
        echo "Game Added";
    }
}

//SoldItems***********************************
// Creates Ticket Item based on the last entereed Ticket and updates inventory if Sale
function createTicketItem($ticket, $item){
    global $pdo;

    $product = getProduct($item);
    $name = $product['name'];
    $qty = 1;
    
    $sql = "INSERT INTO ticketitems (productID, name, ticketID, quantity) VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);    
    $query->bindValue(1, $item);
    $query->bindValue(2, $name);
    $query->bindValue(3, $ticket);
    $query->bindValue(4, $qty);
    
    $query->execute();        

    if($_GET['f'] == 'sell'){
        updateInventory($item);
    }
}
// Returns Ticket Items based on ticketID
function getTicketItems($ticket){
    global $pdo;
    global $games;
    global $sales;
    global $product;

    if($_GET['f'] == 'sales'){
        $sql = "SELECT productID FROM ticketitems WHERE ticketID = $ticket";
        $query = $pdo->prepare($sql);
        $query->execute();
        
        $orders = $query->fetchAll(); 

        foreach($orders as $order){
            getProductPrice($order['productID']);
        }
    }
    else{
        $sql = "SELECT name FROM ticketitems WHERE ticketID = $ticket";
        $query = $pdo->prepare($sql);
        $query->execute();
        
        $orders = $query->fetchAll();

        foreach($orders as $order){
            for($i = 0; $i < sizeof($games); $i++){
                if($order['name'] == $games[$i]){
                    $sales[$i] += 1;
                    break;
                }
            }
        }
    } 
}
// Returns productID based on ticketID
function getTicketItem($ticket){
    global $pdo;

    $sql = "SELECT productID FROM ticketitems WHERE ticketID = $ticket";
    $query = $pdo->prepare($sql);
    $query->execute();
    
    return $query->fetchAll();
}
// Returns productID & name based on ticketID
function getTicketItemInfo($ticket){
    global $pdo;

    $sql = "SELECT productID, name FROM ticketitems WHERE ticketID = $ticket";
    $query = $pdo->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}

//Specialty************************************
// Returns all accessoryIDs 
function getAllAccessoryIDs(){
    global $pdo;

    $sql = "SELECT accessoryID FROM accessories";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}
// Returns Accessories based on accessoryID
function GetSpecialByID($sID){
    global $pdo;
    $sql ="SELECT * FROM accessories WHERE accessoryID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();
}
// Returns accessoryID & name based on accessoryID
function getAllSoldMisc($id){
    global $pdo;
    
    $sql = "SELECT accessoryID, name FROM accessories WHERE accessoryID = '$id'";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}
// Returns all time selling specialty items in JSON
function getAllTimeMisc(){
    global $pdo;
    global $misc;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getMiscSales($ticket['ticketID']);
    }     

    $json = json_encode($misc);

    echo $json;
}
// Speciality item sales logic
function getMiscSales($ticket){
    global $pdo;
    global $misc;
    
    $order = getTicketItemInfo($ticket);

    foreach($order as $item){
        $product = getAllSoldMisc($item['productID']);       
        if($product){
            if(sizeOf($misc) > 0){
                for($i = 0; $i < sizeOf($misc); $i++){    
                    if($product[0]['accessoryID'] == $misc[$i]->upc){
                        $misc[$i]->sales += 1;
                        break;
                    }
                    else if($i == sizeof($misc) - 1){      
                        array_push($misc, (object)["upc"=>$product[0]['accessoryID'], "product"=>$product[0]['name'], "sales"=>1]);
                        break;
                    }
                }
            }
            else{
                array_push($misc, (object)["upc"=>$product[0]['accessoryID'], "name"=>$product[0]['name'], "sales"=>1]);
            }
        } 
    }          
}
// Adds product to  Accessory Table
function addSpecialty($product){
    global $pdo;

    $id = $product->id;
    $name = $product->name;
    $description = $product->description;
    $type = $product->genre;

    $sql = "INSERT INTO accessories VALUES(?, ?, ?, ?)";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $id);
    $query->bindValue(2, $name);
    $query->bindValue(3, $description);
    $query->bindValue(4, $type);

    $query->execute();
}

//Ticket*****************************
// Creates Transaction Ticket for both Sales and Buybacks
function createTicket(){      
    if(isset($_GET['cart'])){

        global $pdo;

        $users = getUserByUsername();
        $user = $users[0];
        $customer = 1;            
        $date = date("Y-m-d");

        if($_GET['f'] == 'sell'){
            $type = 'sale';
        }
        if($_GET['f'] == 'buy'){
            $type = 'buy';
        }
        
        $sql = "INSERT INTO tickets (customerID, userID, ticketType, orderDate) VALUES(?, ?, ?, ?)";
        $query = $pdo->prepare($sql);
        $query->bindValue(1, $customer);
        $query->bindValue(2, $user);
        $query->bindValue(3, $type);
        $query->bindValue(4, $date);
        
        $query->execute();

        $cart = json_decode($_GET['cart'], true);
        
        foreach($cart as $inner){
            if(is_array($inner)){
                foreach($inner as $product){
                    if(is_array($product)){
                        foreach($product as $item){
                            setcookie("productID", $item['productID']);                                
                            $tickets = getCurrentTicket();
                            foreach($tickets as $array){
                                if(is_array($array)){
                                    for($i = 0; $i < $inner['qty']; $i++){
                                        createTicketItem($array['ticketID'], $item['productID']);
                                    }                                    
                                break;
                                }
                            }           
                        }
                    }
                }
            }
        }
    }
    $message = true;
    $json = $message;
    echo json_encode($json);
} 
// Returns the last added Ticket's ticketID
function getCurrentTicket(){
    global $pdo;

    $sql = "SELECT ticketID FROM tickets ORDER BY ticketID DESC LIMIT 1";

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetchAll();
}
// Returns all ticketIDs based on date range given
function getAllTicketIDs(){
    global $pdo;

    $currentDay = date('Y-m-d');
    $weekAgo = date("Y-m-d", strtotime("previous monday"));
    $monthAgo = date('Y-m').'-01';
    $yearlySales = date('Y').'-01-01';

    if(isset($_GET['range'])){
        if($_GET['range'] == 'Weekly'){
            $sql = "SELECT ticketID FROM tickets WHERE orderDate BETWEEN '$weekAgo' AND '$currentDay'";

            $query = $pdo->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }
        else if($_GET['range'] == 'Monthly'){
            $sql = $sql = "SELECT ticketID FROM tickets WHERE orderDate BETWEEN '$monthAgo' AND '$currentDay'";
            
            $query = $pdo->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        } 
        else{
            $sql = $sql = "SELECT ticketID FROM tickets WHERE orderDate BETWEEN '$yearlySales' AND '$currentDay'";
            
            $query = $pdo->prepare($sql);
            $query->execute();

            return $query->fetchAll();
        }
    }
    else{
        $sql = "SELECT ticketID FROM tickets";

            $query = $pdo->prepare($sql);
            $query->execute();

            return $query->fetchAll();
    }
           
}
// Returns Ticket based on ticketID
function GetTicketByID($sID){
    global $pdo;
    $sql ="SELECT * FROM ticket WHERE ticketID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetch();

}
// Returns Ticket based on customerID
function GetTicketByCustomerID($sID){
    global $pdo;
    $sql ="SELECT * FROM ticket WHERE customerID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns Ticket based on userID
function GetTicketByUserID($sID){
    global $pdo;
    $sql ="SELECT * FROM ticket WHERE userID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();    
}

//Users*******************************************
// Returns all User Sales
function getUserSales(){
    global $pdo;
    global $users;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getUserID($ticket['ticketID']);
    }

    $json = json_encode($users);

    echo $json;
}
// Returns userID based on ticketID & ticketType
function getUserID($ticket){
    global $pdo;
    global $users;
    global $totalSales;

    $sql = "SELECT userID FROM tickets WHERE ticketID = $ticket AND ticketType = 'sale'";

    $query = $pdo->prepare($sql);
    $query->execute();

    $results = $query->fetchAll();

    $user = $results[0]['userID'];
    $userInfo = getUserByID($user);
    $username = $userInfo['username'];

    if(sizeOf($users) > 0){
        for($i = 0; $i < sizeOf($users); $i++){
            if($username == $users[$i]->user){
                $users[$i]->sales += 1;
                break;
            }
            else if($i == sizeof($users) - 1 && $username != null){      
                array_push($users, (object)["user"=>$username, "sales"=>1]);
                break;
            }
        }
    }
    else{
        array_push($users, (object)["user"=>$username, "sales"=>1]);
    }        
}
// Returns username based on userID
function getUserByID($id){
    global $pdo;

    $sql = "SELECT username FROM users WHERE userID = '$id'";

    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetch();
}
// Returns Users based on level
function GetUserByLevel($sID){
    global $pdo;
    $sql ="SELECT * FROM users WHERE level =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();

}
// Returns userID based on username
function getUserByUsername(){
    global $pdo;

    $user = $_GET['user'];

    $sql = "SELECT userID FROM users WHERE username = '$user'";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetch();
}
// Updates given User's information
function updateUserInfo(){
    global $pdo;

    $user = json_decode($_GET['user']);
    
    $id = $user->id;
    $username = $user->username;
    $password = $user->password;
    $level = $user->level;

    $sql = "UPDATE users SET username=?, password=?, level=? WHERE userID='$user->id'";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $user->username);
    $query->bindValue(2, $user->password);
    $query->bindValue(3, $user->level);

    if($query->execute()){
        $message = "User Updated";
        $json = json_encode($message);
        echo $json;
    }
    else{
        $message = "Update Failed";
        $json = json_encode($message);
        echo $json;
    }
}
// Adds User to Users table
function addUser(){
    global $pdo;

    $user = json_decode($_GET['user']);
    
    $id = $user->id;
    $username = $user->username;
    $password = $user->password;
    $level = $user->level;

    $sql = "INSERT INTO users SET username=?, password=?, level=?";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, $user->username);
    $query->bindValue(2, $user->password);
    $query->bindValue(3, $user->level);

    if($query->execute()){
        $message = "User Added";
        $json = json_encode($message);
        echo $json;
    }
    else{
        $message = "Failed to Add User";
        $json = json_encode($message);
        echo $json;
    }
}
// Sets employed to false on given user
function deleteUser(){
    global $pdo;

    $sql = "UPDATE users SET employed=? WHERE userID='$user->id'";
    $query = $pdo->prepare($sql);
    $query->bindValue(1, false);

    if($query->execute()){
        $message = "User Deleted";
        $json = json_encode($message);
        echo $json;
    }
    else{
        $message = "Failed to Delete User";
        $json = json_encode($message);
        echo $json;
    }
}

//Generations*******************************************
// Returns all Console Generations
function GetAllGenerations(){
    global $pdo;
    $sql ="SELECT * FROM consolegenerations";
    $query = $pdo->query($sql);
    $query->execute();
    $generations = $query->fetchAll();  
    
    $json = json_encode($generations);
    echo $json;
}
// Returns Console Generations based on generationID
function GetGenByID($sID){
    global $pdo;
    $sql ="SELECT * FROM consolegenerations WHERE generationID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns Console Generations based on name
function GetGenByName($sName){
    global $pdo;
    $sql ="SELECT * FROM consolegenerations WHERE name like $sName";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns brandID based on Console Generation name
function getGenerationBrand($sName){    
    global $pdo;

    $sql = "SELECT brandID from consolegenerations WHERE name = '$sName'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $result = $query->fetch();
    $brand = $result['brandID'];

    if($_GET['f'] == 'getbrand'){
        $result = GetBrandByID($brand);    
        $json = json_encode($result['name']);
        echo $json;
    }
    else{
        return $brand;
    }
}
// Returns generationID based on name
function getGenerationID($sName){
    global $pdo;

    $sql = "SELECT generationID FROM consolegenerations WHERE name = '$sName'";
    $query = $pdo->prepare($sql);
    $query->execute();

    return $query->fetch();
}

//Accessories*******************************************
// Returns Accessory based on accessoryID
function GetAccessByID($sID){
    global $pdo;
    $sql ="SELECT * FROM accessories WHERE accessoryID =$sID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns Accessory based on consoleID
function GetAccessByConsole($sConsoleID){
    global $pdo;
    $sql ="SELECT * FROM accessories WHERE consoleID =$sConsoleID";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}
// Returns Accessory based on description
function GetAccessByDesc($sDesc){
    global $pdo;
    $sql ="SELECT * FROM accessories WHERE description =$sDesc";
    $pdostate = $pdo->query($sql);
    return $pdostate->fetchAll();
}

//Categories*******************************************
// Category Sales Logic
function getCategory($ticket){    
    global $pdo;    
    global $categories;
    
    $items = getTicketItem($ticket);  
    $gameIDs = getAllGameIDs();
    $consoleIDs = getAllConsoleIDs();
    $equipmentIDs = getAllEquipmentIDs();
    $miscIDs = getAllAccessoryIDs();

    for($i = 0; $i < sizeof($gameIDs) + 1; $i++){
        if($items[$i]['productID'] == $gameIDs[$i]['gameID']){
            $categories[0] += 1;
        }
    }
    for($i = 0; $i < sizeof($consoleIDs) + 1; $i++){
        if($items[$i]['productID'] == $consoleIDs[$i]['consoleID']){
            $categories[1] += 1;
        }
    }
    for($i = 0; $i < sizeof($equipmentIDs) + 1; $i++){
        if($items[$i]['productID'] == $equipmentIDs[$i]['equipmentID']){
            $categories[2] += 1;
        }
    }
    for($i = 0; $i < sizeof($miscIDs) + 1; $i++){
        if($items[$i]['productID'] == $miscIDs[$i]['accessoryID']){
            $categories[3] += 1;
        }
    }        
}
// Returns total category sales
function getTotalCategorySales(){
    global $pdo;
    global $categories;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getCategory($ticket['ticketID']);
    }
    
    $json = json_encode($categories);    
    echo $json;
}
// Routes to proper function based on category given
function addToCategory($product){
    $category = $product->category;
    switch($category){
        case 'games':
            addGame($product);
        break;

        case 'consoles':
            addConsole($product);
        break;

        case 'equipment':
            addEquipment($product);
        break;

        case 'specialty':
            addSpecialty($product);
        break;
    }
}

//Sales*******************************************
function getTotalSales(){
    global $pdo;
    global $totalSales;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getTicketItems($ticket['ticketID']);
    }

    $json = json_encode($totalSales);
    
    echo $json;
}
// Returns top order days (Mon-Sun)
function getTopOrderDays(){
    global $pdo;
    global $sales;
    global $week;

    $tickets = getAllTicketIDs();

    foreach($tickets as $ticket){
        getOrderDay($ticket['ticketID']);
    }

    $json = json_encode($sales);    
    echo $json;    
}
// Returns orderDate based on ticketID
function getOrderDay($ticket){
    global $pdo;
    global $week;
    global $sales;        

    $sql = "SELECT orderDate FROM tickets WHERE ticketID = $ticket";
    $query = $pdo->prepare($sql);
    $query->execute();
    
    $order = $query->fetch();

    $dayofweek = date('l', strtotime($order['orderDate']));

    for($i = 0; $i < sizeof($week); $i++){
        if($dayofweek == $week[$i]){
            $orderDay = $dayofweek;
            $sales[$i] += 1;
            return $orderDay;
            break;
        }
    }
}

//Inventory*******************************************
function updateInventory($id){
    if($_GET['f'] == 'sell'){
        deductFromStock($id);
    }
}
// Decrements Stock by 1 of given Product
function deductFromStock($id){
    global $pdo;

    $sql = "UPDATE products SET stock = stock - 1 WHERE productID = $id AND stock > 0";

    $query = $pdo->prepare($sql);
    $query->execute();
}
// Updates given Product information
function editInventoryItem(){
    global $pdo;

    if(isset($_GET['product'])){
        $product = json_decode($_GET['product']);   

        $id = $product->id;
        $name = $product->name;
        $description = $product->description;
        $price = $product->price;
        $used = $product->used;
        $stock = $product->stock;

        $sql = "UPDATE products SET name=?, description=?, price=?, used=?, stock=? WHERE productID='$id'";
        $query = $pdo->prepare($sql);
        $query->bindValue(1, $name);
        $query->bindValue(2, $description);
        $query->bindValue(3, $price);
        $query->bindValue(4, $used);
        $query->bindValue(5, $stock);

        if($query->execute()){
            $message = "updated";
        
            $json = json_encode($message);

            echo $json;
        }           

    }
}
// Adds product to Products table, then calls function to add to subsequent Table
function addInventoryItem(){
    global $pdo;

    if(isset($_GET['product'])){
        $product = json_decode($_GET['product']); 

        $id = $product->id;
        $name = $product->name;
        $description = $product->description;
        $price = $product->price;
        $used = $product->used;
        $stock = $product->stock;
        $category = $product->category;
        $console = $product->console;
        $generation = $product->generation;
        $brand = $product->brand;

        $result = getProduct($id);

        if(!$result){            
            $sql = "INSERT INTO products VALUES(?, ?, ?, ?, ?, ?)";
            $query = $pdo->prepare($sql);
            $query->bindValue(1, $id);
            $query->bindValue(2, $name);
            $query->bindValue(3, $description);
            $query->bindValue(4, $price);
            $query->bindValue(5, $used);
            $query->bindValue(6, $stock);

            if($query->execute()){
                addToCategory($product);
                $message = "Product Added";            
                $json = json_encode($message);
                echo $json;
            }  
        }
    }
}

//search functions *******************************************
// Searches Equipment Table
function SearchEquipment($sID="",$sConsole="",$sName="",$sType=""){
    if(!empty($sID)){
        return GetEquipmentByID($sID);

    }
    if(!empty($sName)){
        $aryEquipmentName = GetEquipmentByName($sName);
        if(empty($sConsole)&&empty($sType)){
            return $aryEquipmentName;
        }
    }
    if(!empty($sConsole)){
       $aryConsoles= GetConsolesByName($sConsole);
       $aryEquipmentConsoles =[];
        foreach( $aryConsoles as$aryConsole ){
            $aryEquipmentConsoles = array_unique(array_merge(GetEquipmentByConsoleID($aryConsole['equipmentID']),$aryEquipmentConsoles));
        }
        if(empty($sName) && empty($sType)){  
            return $aryEquipmentConsoles;
        }
    }
    if(!empty($aryEquipmentName) &&! empty($aryEquipmentConsoles)){
        return array_unique(array_merge($aryEquipmentName,$aryEquipmentConsoles));
    }
}
// Searches Consoles Table
function SearchConsoles($sID="",$sName ="",$sGen="",$sBrand=""){ 
    if(!empty($sID)){
        return GetConsoles($sID);

    }
    if(!empty($sName)){
        $aryConsoleName = GetConsolesByName($sName);
        if(empty($sGen)&&empty($sBrand)){
            return $aryConsoleName;
        }
    }
    if(!empty($sGen)){
        $aryGens = GetGenByName($sGen);
        $aryConsolesGen =[];
        foreach($aryGens as$aryGen  ){
            $aryConsolesGen = array_unique(array_merge(GetConsolesByGenID($aryGen['generationid']),$aryConsolesGen));
        }
       if(empty($sName)&&empty($sBrand)){
            return $aryConsolesGen;
        }
    }
    if(!empty($sBrand)){
        $aryBrands = GetBrandByName($sBrand);
        $aryConsolesBrand =[];
        foreach($aryBrands as$aryBrand  ){
            $aryConsolesBrand = array_unique(array_merge(GetConsolesByBrandID($aryBrand['brandid']),$aryConsolesBrand));
        }
       if(empty($sName)&&empty($sBrand)){
            return $aryConsolesBrand;
        }
    }
    if(!empty($aryConsolesName)&&!empty($aryConsolesGen)){
        if(empty($aryConsolesBrand)){
            return array_unique(array_merge($aryConsoleName,$aryConsolesGen));
        }
        else{
            $aryConsoles =array_unique(array_merge($aryConsoleName,$aryConsolesGen));
            return array_unique(array_merge($aryConsoles,$aryConsolesBrand));
        }
    }
}
// Searches Accessories Table
function SearchAccessories($sID="",$sConsole="",$sDesc=""){
    if(!empty($sID)){
        return GetAccessByID($sID);
    }
    if(!empty($sConsole)){
        $aryConsoles= GetConsolesByName($sConsole);
        $aryAccessConsoles =[];
        foreach($aryConsoles as $aryConsole ){
            $aryAccessConsoles = array_unique(array_merge(GetAccessByConsole($aryConsole['accessoryID']),$aryAccessConsoles));
        }
        if(empty($sDesc)){  
            return $aryAccessConsoles;
        }
    }
    if(!empty($sDesc)){
        $aryAccessDesc = [];
        $aryAccessDesc = GetAccessByDesc($sDesc);
        if(empty($sConsole)){
            return $aryAccessDesc;
        }
    }
    if(!empty($aryAccessConsoles)&&!empty($aryAccessDesc)){
        return array_unique(array_merge($aryAccessDesc,$aryAccessConsoles));
    }
}
// Searches Games Table
function SearchGames($sID="",$sConsole="",$sName="",$sDate="",$sGenre=""){
    $aryGames=[];
    if(!empty($sID)){
        return GetGamesByGameID($sID);
    }
    if(!empty($sConsole)){
        $aryConsoles= GetConsolesByName($sConsole);
        $aryGameConsoles =[];
        foreach($aryConsoles as $aryConsole ){
            $aryGameConsoles = array_unique(array_merge(GetGamesByConsoleID($aryConsole['consoleID']),$aryGameConsoles));
        }
        if(empty($sName)&&empty($sDate)&&empty($sGenre)){  
            return $aryGameConsoles;
        }
        else{
            $aryGames =array_unique(array_merge($aryGames,$aryGameConsoles));
        }
    }
    if(!empty($sName)){
        $aryGameName = GetGamesByName($sName);
        if(empty($sConsole)&&empty($sDate)&&empty($sGenre)){  
            return $aryGameName;
        }
        else{
            $aryGames =array_unique(array_merge($aryGames,$aryGameName));
        }
    }
    if(!empty($sDate)){
        $aryGameDate = GetGamesByDate($sDate);
        if(empty($sConsole)&&empty($sName)&&empty($sGenre)){  
            return $aryGameDate;
        }
        else{
            $aryGames =array_unique(array_merge($aryGames,$aryGameDate));
        }
    }
    if(!empty($sGenre)){
        $aryGameGenre = GetGamesByGenre($sGenre);
        if(empty($sConsole)&&empty($sName)&&empty($sDate)){  
            return $aryGameGenre;
        }
        else{
            $aryGames =array_unique(array_merge($aryGames,$aryGameGenre));
        }
    }
    if(!empty($aryGames)){
        return $aryGames;
    }   
   
}
// Searches ConsoleGenerations Table
function SearchGens($sID="",$sName=""){
    if(!empty($sID)){
        return GetGenByID($sID);
    }
    if(!empty($sName)){
        return GetGenByName($sName);
    }
}
// Searches Brands Table
function SearchBrands($sID="",$sName=""){
    if(!empty($sID)){
        return GetBrand($sID);
    }
    if(!empty($sName)){
        return GetBrandByName($sName);
    }
}

// Returns All Products associated with the Games Table
function getSearchGames(){
    $games = GetAllGames();
    $results = array();
    foreach($games as $game){
        array_push($results, getProduct($game));
    }
    $json = json_encode($results);
    echo $json;
}
// Returns All Products associated with the Consoles Table
function getSearchConsoles(){
    $consoles = GetAllConsoleIDs();
    $results = array();
    foreach($consoles as $console){
        array_push($results, getProduct($console['consoleID']));
    }
    $json = json_encode($results);
    echo $json;
}
// Returns All Products associated with the Equipment Table
function getSearchEquipment(){
    $equipment = GetAllEquipmentIDs();
    $results = array();
    foreach($equipment as $item){
        array_push($results, getProduct($item['equipmentID']));
    }
    $json = json_encode($results);
    echo $json;
}
// Returns All Products associated with the Accessory Table
function getSearchSpecialty(){
    $specialty = GetAllAccessoryIDs();
    $results = array();
    foreach($specialty as $misc){
        array_push($results, getProduct($misc['accessoryID']));
    }
    $json = json_encode($results);
    echo $json;
}

