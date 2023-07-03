<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

class DB{
    private $host;
    private $username;
    private $password;
    private $db;
    
    function set(){
        $this->host = "localhost";
        $this->username = "seminaram_ZGC";
        $this->password = "ZGC!2023vatanpoor";
        $this->db = "seminaram_DB";
    }
    
    function connect(){
        $this->set();
        $host = $this->host;
        $username = $this->username;
        $password = $this->password;
        $db = $this->db;
        
        $conn = new mysqli($host, $username, $password, $db);
        
        if ($conn->connect_error) {
            return(0);//failed
        }else{
            return($conn);//success
        }
    }
}


class Queries{
    
    function insert_order($box_id, $product_id, $num){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "INSERT INTO orders (box_id, product_id, num)
            VALUES ($box_id, $product_id, $num)";
            
            if ($conn->query($sql) === TRUE) {
              echo "New record created successfully!</br>";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    
    function delete_order(){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "DELETE FROM orders WHERE product_id>10";

            if ($conn->query($sql) === TRUE) {
              echo "Record deleted successfully!</br>";
            } else {
              echo "Error deleting record: " . $conn->error;
            }

        }
    }
    
    function who_has_order(){//The name and age of all the people who have an order
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT name, age
            FROM user
            JOIN user_address ON user.id = user_address.user_id
            JOIN box ON user_address.id = box.address_id
            Group By name";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['name'] . ": " . $row['age'] . "years old" . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function product_profit(){//Profit from the sale of each product
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                $id = $row['id'];
                  
                $sql2 = "SELECT num FROM orders WHERE product_id='$id'";
                $result2 = $conn->query($sql2);
                    
                if ($result2->num_rows > 0) {
                    // output data of each row
                    while($row2 = $result2->fetch_assoc()) {
                        $amount = $row2['num'] * ($row['sell_price'] - $row['buy_price']);
                    }
                }
                
                echo $row['name'] . ": " . $amount . "$" . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function product_profit_by_id($p_id){//Profit from the sale of each product
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT * FROM product WHERE id='$p_id'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                $id = $row['id'];
                  
                $sql2 = "SELECT num FROM orders WHERE product_id='$id'";
                $result2 = $conn->query($sql2);
                    
                if ($result2->num_rows > 0) {
                    // output data of each row
                    while($row2 = $result2->fetch_assoc()) {
                        $amount = $row2['num'] * ($row['sell_price'] - $row['buy_price']);
                    }
                }
                
                return($amount);
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function tag_profit(){//Profit from the sale of each product
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT id, product_tag.product_id, name
            FROM tag
            JOIN product_tag ON tag.id = product_tag.tag_id
            GROUP BY name";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                
                echo $row['name'] . $this->product_profit_by_id($row['product_id']) . "$" . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function top_five(){//top five production
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT p.name, SUM(o.num) AS total_sales
            FROM product AS p
            JOIN orders AS o ON o.product_id = p.id
            GROUP BY p.name
            ORDER BY total_sales DESC
            LIMIT 5";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              $rank = 1;
              while($row = $result->fetch_assoc()) {
                
                
                echo $rank . ") " . $row['name'] . "</br>";
                $rank = $rank+1;
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function avg_month(){//top five production
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT AVG((o.num * (p.sell_price - p.buy_price))) AS average_profit
            FROM orders AS o
            JOIN product AS p ON o.product_id = p.id
            JOIN box AS b ON o.box_id = b.id
            WHERE b.month = 1";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                
                echo "month 2023/1/... " . $row['average_profit'] . "$" . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function user_order($first_day, $last_day){//Write a query that displays the total cost and number of orders for each customer in a specific time period to giv
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT
                u.id AS user_id,
                u.name AS user_name,
                COUNT(o.id) AS num_orders,
                SUM(b.delivery_cost) AS total_cost
            FROM
                user u
                JOIN user_address ua ON u.id = ua.user_id
                JOIN box b ON ua.id = b.address_id
                JOIN orders o ON b.id = o.box_id
            WHERE
                b.year = 2023
                AND b.month = 1
                AND b.day > '$first_day'
                AND b.day < '$last_day'
            GROUP BY
                u.id, u.name";

            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              $t = 0;
              while($row = $result->fetch_assoc()) {
                
                $t = $row['total_cost'] + $t;
                echo $row['total_cost'] . "$" . "</br>";
              }
              
              echo "AVG: " . $t/($last_day-$first_day-1) . "</br>";
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function time_between_orders(){//Write a query that displays the average time between orders for each customer.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT 
                        user.id, 
                        user.name, 
                        AVG(
                            TIMESTAMPDIFF(
                                MINUTE, 
                                CONCAT_WS('-', box.year, box.month, box.day), 
                                box.time
                            )
                        ) AS average_time_between_orders
                    FROM 
                        orders
                    INNER JOIN 
                        user ON orders.user_id = user.id
                    INNER JOIN 
                        box ON orders.box_id = box.id
                    GROUP BY 
                        user.id, 
                user.name";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['average_time_between_orders'] . "</br>";
              }
              
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function buy_more_one_tag(){//Write a query that displays customers who have purchased from more than 1 categories.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT u.id, u.name, u.email
                    FROM user u
                    JOIN user_address a ON a.user_id = u.id
                    JOIN box b ON b.address_id = a.id
                    JOIN orders o ON o.box_id = b.id
                    JOIN product p ON p.id = o.product_id
                    JOIN product_tag pt ON pt.product_id = p.id
                    JOIN tag t ON t.id = pt.tag_id
                    GROUP BY u.id, u.name, u.email
                    HAVING COUNT(DISTINCT t.id) > 1";

            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['name'] . "</br>";
              }
              
            } else {
              echo "0 results";
            }
        }
    }
    
    function top3_tag(){//Write a query that displays the 3 best-selling products of each category along with their average profit.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            
            /*
            SELECT name
            FROM product
            INNER JOIN orders ON product.id = orders.product_id
            */
            $sql = "SELECT t.name AS tag_name, p.name AS product_name, AVG(p.sell_price - p.buy_price) AS average_profit
                    FROM product_tag pt
                    JOIN product p ON pt.product_id = p.id
                    JOIN tag t ON pt.tag_id = t.id
                    GROUP BY t.name, p.name
                    ORDER BY SUM(p.inventory) DESC, average_profit DESC
                    LIMIT 3";

            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              $rank = 1;
              while($row = $result->fetch_assoc()) {
                  
                echo $rank . ") " . $row['tag_name'] . "</br>";
                $rank = $rank+1;
              }
              
            } else {
              echo "0 results";
            }
        }
    }
    
    function avg_profit(){//Write a query that calculates the average total profit for each day of the week.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT DAYNAME(CONCAT(year, '-', month, '-', day)) AS day_of_week, 
            AVG(orders.num * (product.sell_price - product.buy_price) - box.delivery_cost) AS avg_profit 
            FROM 
            box 
            JOIN orders ON orders.box_id = box.id 
            JOIN product ON product.id = orders.product_id 
            GROUP BY day_of_week";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['day_of_week'] . ": " . $row['avg_profit'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function Related_products(){//Write a query to find the three products that are purchased more than the rest of the products together
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT 
              orders.product_id, 
              COUNT(*) AS num_purchases
            FROM 
              orders 
              JOIN orders AS o2 ON orders.box_id = o2.box_id AND orders.product_id < o2.product_id
              JOIN product AS p1 ON orders.product_id = p1.id
            GROUP BY 
              orders.product_id
            HAVING 
              COUNT(*) >= 3
            ORDER BY 
              num_purchases DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['num_purchases'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function valuable_city(){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT ua.city, SUM((p.sell_price - p.buy_price) * o.num) AS total_profit
                    FROM user_address ua
                    JOIN box b ON ua.id = b.address_id
                    JOIN orders o ON b.id = o.box_id
                    JOIN product p ON o.product_id = p.id
                    GROUP BY ua.city
                    ORDER BY total_profit DESC
                    LIMIT 3";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['city'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function percent_profit_of_each_tag(){//Write a query that finds the percentage of revenue generated by each category.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT t.name AS name, SUM(o.num * (p.sell_price - p.buy_price)) / SUM(o.num * p.sell_price) * 100 AS percentage_income
            FROM product_tag pt
            JOIN product p ON pt.product_id = p.id
            JOIN orders o ON o.product_id = p.id
            JOIN box b ON o.box_id = b.id
            JOIN tag t ON pt.tag_id = t.id
            GROUP BY t.name";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['name'] . ": " . $row['percentage_income'] . "$" . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    
    function favorite(){//Write a query that finds each customer's favorite product.
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT u.name AS customer_name, p.name AS favorite_product
                    FROM user AS u
                    JOIN user_address a ON a.user_id = u.id
                    JOIN box b ON a.id = b.address_id
                    JOIN orders o ON b.id = o.box_id
                    JOIN product AS p ON o.product_id = p.id
                    JOIN product_tag AS pt ON p.id = pt.product_id
                    JOIN tag AS t ON pt.tag_id = t.id
                    GROUP BY customer_name";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['customer_name'] . ": " . $row['favorite_product'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function top_user(){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT u.id, u.name, AS name COUNT(*) AS total_orders
                    FROM user u
                    JOIN user_address a ON a.user_id = u.id
                    JOIN box b ON a.id = b.address_id
                    JOIN orders o ON b.id = o.box_id
                    GROUP BY u.id, u.name
                    HAVING COUNT(*) > (
                      SELECT COUNT(*)
                      FROM user u2
                      INNER JOIN orders o2 ON u2.id = o2.user_id
                      GROUP BY u2.id
                      ORDER BY COUNT(*) DESC
                      LIMIT 1
                    )";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['name'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function top_sell(){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT 
              YEAR(orders.date) AS sales_year 
            FROM 
              orders 
            GROUP BY 
              YEAR(orders.date) 
            ORDER BY 
              SUM(orders.num * orders.unit_price) DESC 
            LIMIT 1";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                echo $row['sales_year'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    function increase_month(){
        // Create connection
        $db = new DB;
        $conn = $db->connect();
        // Check connection
        if ($conn == 0) {
            return(101);
        }else{
            $sql = "SELECT 
              YEAR(orders.date) AS sales_year, 
              MONTH(orders.date) AS sales_month, 
              SUM(orders.num * orders.unit_price) AS monthly_sales, 
              LAG(SUM(orders.num * orders.unit_price)) OVER (ORDER BY YEAR(orders.date), MONTH(orders.date)) AS previous_month_sales, 
              ((SUM(orders.num * orders.unit_price) - LAG(SUM(orders.num * orders.unit_price)) OVER (ORDER BY YEAR(orders.date), MONTH(orders.date))) / LAG(SUM(orders.num * orders.unit_price)) OVER (ORDER BY YEAR(orders.date), MONTH(orders.date))) * 100 AS sales_growth_rate 
            FROM 
              orders 
            GROUP BY 
              YEAR(orders.date), 
              MONTH(orders.date)";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                
                //echo $row['sales_year'] . "</br>";
              }
            } else {
              echo "0 results";
            }
        }
    }
    
    
    
    
    
    
}

$sql = new Queries;
$sql->insert_order(1, 2, 25);
$sql->delete_order();

$sql->who_has_order();
$sql->product_profit();
$sql->tag_profit();
$sql->top_five();
$sql->avg_month();
$sql->user_order();
$sql->time_between_orders(5, 10);
$sql->buy_more_one_tag();
$sql->top3_tag();
$sql->avg_profit();
$sql->Related_products();
$sql->valuable_city();
$sql->percent_profit_of_each_tag();
$sql->favorite();
?>
