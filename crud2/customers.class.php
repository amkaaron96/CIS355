<?php

class Customers {
    
    public $id;
    
    public $name;
    public $email;
    public $mobile;
    
    private $nameError = null;
    private $emailError = null;
    private $mobileError = null;
    
    private $title = "Customer";
    
    function create_record() { // display create form
        echo "
        <html>
            <head>
                <title>Create a $this->title</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    "; 
        echo "
            </head>

            <body>
                <div class='container'>

                    <div class='span10 offset1'>
                        <p class='row'>
                            <h3>Create a $this->title</h3>
                        </p>
                        <form class='form-horizontal' action='customer.php?fun=11' method='post'>                        
                    ";
        $this->control_group("name", $this->nameError, $this->name);
        $this->control_group("email", $this->emailError, $this->email);
        $this->control_group("mobile", $this->mobileError, $this->mobile);
        echo " 
                            <div class='form-actions'>
                                <button type='submit' class='btn btn-success'>Create</button>
                                <a class='btn' href='customer.php'>Back</a>
                            </div>
                        </form>
                    </div>

                </div> <!-- /container -->
            </body>
        </html>
                    ";
    }
    
    function list_records() {
        echo "
        <html>
            <head>
                <title>$this->title" . "s" . "</title>
                    ";
        echo "
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
                    ";  
        echo "
            </head>
            <body>
                <div class='container'>
                    <p class='row'>
                        <h3>$this->title" . "s" . "</h3>
                    </p>
                    <p>
                        <a href='customer.php?fun=1' class='btn btn-success'>Create</a>
                    </p>
                    <div class='row'>
                        <table class='table table-striped table-bordered'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    ";
        $pdo = Database::connect();
        $sql = "SELECT * FROM customers ORDER BY id DESC";
        foreach ($pdo->query($sql) as $row) {
            echo "<tr>";
            echo "<td>". $row["name"] . "</td>";
            echo "<td>". $row["email"] . "</td>";
            echo "<td>". $row["mobile"] . "</td>";
            echo "<td width=250>";
            echo "<a class='btn' href='customer.php?fun=2&id=".$row["id"]."'>Read</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-success' href='customer.php?fun=3&id=".$row["id"]."'>Update</a>";
            echo "&nbsp;";
            echo "<a class='btn btn-danger' href='customer.php?fun=4&id=".$row["id"]."'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        Database::disconnect();        
        echo "
                            </tbody>
                        </table>
                    </div>
                </div>

            </body>

        </html>
                    ";  
    } // end list_records()

    function read_record () {
        $id = $_GET['id'];        
        $pdo = Database::connect();
        $sql = "SELECT * FROM customers WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        Database::disconnect();
        echo "
        <!DOCTYPE html>
        <html lang='en'>
            <head>
            <meta charset='UTF-8'>
                <link   href='css/bootstrap.min.css' rel='stylesheet'>
                <script src='js/bootstrap.min.js'></script>
            </head>
 
            <body>
            <div class='container'>
                <div class='span10 offset1'>
                    <div class='row'>
                        <h3>Read a Customer</h3>
                    </div>
                     
                    <div class='form-horizontal'>
                      <div class='control-group'>
                        <label class='control-label'>Name</label>
                        <div class='controls'>
                            <label class='checkbox'>";
                                echo $data['name'];
            echo "                </label>
                        </div>
                      </div>
                      <div class='control-group'>
                        <label class='control-label'>Email Address</label>
                        <div class='controls'>
                            <label class='checkbox'>";
                                echo $data['email'];
            echo "                </label>
                        </div>
                      </div>
                      <div class='control-group'>
                        <label class='control-label'>Mobile Number</label>
                        <div class='controls'>
                            <label class='checkbox'>";
                                echo $data['mobile'];
            echo "                </label>
                        </div>
                      </div>
                        <div class='form-actions'>
                          <a class='btn' href='customer.php'>Back</a>
                       </div>
                    </div>
                </div>
                </div> <!-- /container -->
                </body>
        </html>
        ";
    }
    
    function update_record() {
        $id = $_GET['id'];
        
        if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $emailError = null;
        $mobileError = null;
         
        // keep track post values
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
         
        // validate input
        $valid = true;
        if (empty($name)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
         
        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }
         
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE customers  set name = ?, email = ?, mobile =? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$email,$mobile,$id));
            Database::disconnect();
            header("Location: customer.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM customers where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $name = $data['name'];
        $email = $data['email'];
        $mobile = $data['mobile'];
        Database::disconnect();
    }
        
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='utf-8'>
            <link   href='css/bootstrap.min.css' rel='stylesheet'>
            <script src='js/bootstrap.min.js'></script>
        </head>
         
        <body>
            <div class='container'>
             
                        <div class='span10 offset1'>
                            <div class='row'>
                                <h3>Update a Customer</h3>
                            </div>
                     
                            <form class='form-horizontal' action='customer.php?fun=3&id="; echo $id; echo " ' method='post'>
                              <div class='control-group"; echo !empty($nameError)?'error':''; echo "'>
                                <label class='control-label'>Name</label>
                                <div class='controls'>
                                    <input name='name' type='text'  placeholder='Name' value='"; echo !empty($name)?$name:''; echo "'>";
                                    if (!empty($nameError)): 
                                        echo "<span class='help-inline'>"; echo $nameError; echo "</span>";
                                    endif;
                                echo "</div>
                              </div>
                              <div class='control-group"; echo !empty($emailError)?'error':''; echo "'>
                                <label class='control-label'>Email Address</label>
                                <div class='controls'>
                                    <input name='email' type='text' placeholder='Email Address' value='"; echo !empty($email)?$email:''; echo "'>";
                                    if (!empty($emailError)): 
                                        echo "<span class='help-inline'>"; echo $emailError; echo "</span>";
                                    endif;
                                echo "</div>
                              </div>
                              <div class='control-group"; echo !empty($mobileError)?'error':''; echo "'>
                                <label class='control-label'>Mobile Number</label>
                                <div class='controls'>
                                    <input name='mobile' type='text'  placeholder='Mobile Number' value='"; echo !empty($mobile)?$mobile:''; echo "'>";
                                    if (!empty($mobileError)): 
                                        echo "<span class='help-inline'>"; echo $mobileError; echo "</span>";
                                    endif;
                                echo "</div>
                              </div>
                              <div class='form-actions'>
                                  <button type='submit' class='btn btn-success'>Update</button>
                                  <a class='btn' href='customer.php'>Back</a>
                                </div>
                            </form>
                        </div>
                         
            </div> <!-- /container -->
          </body>
        </html>";
    }
    
    function control_group ($label, $labelError, $val) {
        echo "<div class='control-group";
        echo !empty($labelError) ? 'error' : '';
        echo "'>";
        echo "<label class='control-label'>$label</label>";
        echo "<div class='controls'>";
        echo "<input name='$label' type='text' placeholder='$label' value='";
        echo !empty($val) ? $val : '';
        echo "'>";
        if (!empty($labelError)) {
            echo "<span class='help-inline'>";
            echo $labelError;
            echo "</span>";
        }
        echo "</div>";
        echo "</div>";
    }
    
     function delete_record($id){
        echo "
        <!DOCTYPE html>
        <html>
            <head>
                <title>Record Delete</title>
                <meta charset='UTF-8'>
                <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css' rel='stylesheet'>
                <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js'></script>
            </head>
            <body>
                <div>
                    <p>Are you sure you want to delete this record?</p>
                    <a class='btn btn-success' role='button' href='customer.php'>Cancel</a>
                    <a class='btn btn-danger' role='button' href='customer.php?fun=44&id=".$id."'>Delete</a>
                </div>
            </body>
        </html>
        ";
    }
    
    function delete_db($id) {       
        // delete data
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "DELETE FROM customers  WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        header("Location: customer.php");
    }
    
    function insert_record () {
        // validate input
        $valid = true;
        if (empty($this->name)) {
            $this->nameError = 'Please enter Name';
            $valid = false;
        }

        if (empty($this->email)) {
            $this->emailError = 'Please enter Email Address';
            $valid = false;
        } 
        /*
        else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
        
            $this->emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         */

        if (empty($this->mobile)) {
            $this->mobileError = 'Please enter Mobile Number';
            $valid = false;
        }

        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO customers (name,email,mobile) values(?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($this->name,$this->email,$this->mobile));
            Database::disconnect();
            header("Location: customer.php");
        }
        else {
            $this->create_record();
        }
    }
    
} // end class Customers
?>