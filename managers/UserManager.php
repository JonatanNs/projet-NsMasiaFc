<?php 

class UserManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/
    public function SignUpUser(User $users) : void {
        try{
            $query = $this->db->prepare("INSERT INTO users (id, first_name, last_name, email, password) 
                                        VALUES (NULL, :first_name, :last_name, :email, :password)");
            $parameters = [
                'first_name' => $users->getFirstName(), 
                'last_name' => $users->getLastName(), 
                'email' => $users->getEmail(), 
                'password' => $users->getPassword()
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to create user.");
        }
    }

    /**********************************************************
                    * CHANGE USER INFORMATION *
    **********************************************************/

    public function changeRoles(int $id, string $roles) : void {
        try{
            $query = $this->db->prepare("UPDATE users
                                        SET roles = :roles 
                                        WHERE id = :id");
            $parameters = [
            'id' => $id,
            'roles' => $roles, 
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            // Log the error or handle it as necessary
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change roles in the database.");
        }
    }

    public function changeName(
                                int $id, 
                                string $first_name, 
                                string $last_name
                            ) : void {
        try{
            $query = $this->db->prepare("UPDATE users 
                                        SET first_name = :first_name, last_name = :last_name 
                                        WHERE id = :id"
                                    );
            $parameters = [
            'id' => $id,
            'first_name' => $first_name, 
            'last_name' => $last_name 
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change name.");
        }
    }

    public function changePassword(int $id, string $password) : void {
        $query = $this->db->prepare("UPDATE users 
                                    SET password = :password 
                                    WHERE id = :id"
                                    );
        $parameters = [
        'id' => $id,
        'password' => $password, 
        ];
        $query->execute($parameters);
    }

    public function changeEmail(int $id, string $email) : void {
        try{
            $query = $this->db->prepare("UPDATE users 
                                        SET email = :email 
                                        WHERE id = :id"
                                        );
            $parameters = [
            'id' => $id,
            'email' => $email, 
            ];
            $query->execute($parameters);
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to change email.");
        }
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllUser() : array {
        try{
            $query = $this->db->prepare("SELECT * FROM users");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            $users = [];
            foreach($result as $item){
                $user = new User(
                                    $item["first_name"], 
                                    $item["last_name"], 
                                    $item["email"], 
                                    $item["password"]
                                );
                $user->setId($item["id"]);
                $user->setRoles($item["roles"]);
                $users[] = $item;
            }
            return $users ;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all user.");
        }
    }

    public function getAllUserByEmail(string $email) : ? User {
        try{
            $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $parameters = [
                'email' => $email
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result) {
                $user = new User(
                                    $result["first_name"], 
                                    $result["last_name"], 
                                    $result["email"], 
                                    $result["password"]
                                );
                $user->setId($result["id"]);
                $user->setRoles($result["roles"]);
                return $user;
            }
            return null ;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all user.");
        }
    }

    public function getAllUserById(int $id) : ? User {
        try{
            $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $parameters = [
                'id' => $id
            ];
            $query->execute($parameters);
            $result = $query->fetch(PDO::FETCH_ASSOC);

            if($result) {
                $user = new User(
                                    $result["first_name"], 
                                    $result["last_name"], 
                                    $result["email"], 
                                    $result["password"]
                                );
                $user->setId($result["id"]);
                $user->setRoles($result["roles"]);
                return $user;
            }
            return null ;
        } catch (PDOException $e) {
            error_log("Database error : " . $e->getMessage());
            throw new Exception("Failed to fetch all user by id.");
        }
    }
}
