<?php 

class UserManager extends AbstractManager{

    /**********************************************************
                             * CREATE *
    **********************************************************/

    /***************************
        * CREATE USER *
    ****************************/

    public function SignUpUser(User $users) : void {
        $query = $this->db->prepare("INSERT INTO users (id, first_name, last_name, email, password) 
                                    VALUES (NULL, :first_name, :last_name, :email, :password)");
        $parameters = [
            'first_name' => $users->getFirstName(), 
            'last_name' => $users->getLastName(), 
            'email' => $users->getEmail(), 
            'password' => $users->getPassword()
        ];
        $query->execute($parameters);
    }

    /**********************************************************
                             * FETCH *
    **********************************************************/

    public function getAllUser() : array {
        $query = $this->db->prepare("SELECT * FROM users");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach($result as $item){
            $user = new User($item["first_name"], $item["last_name"], $item["email"], $item["password"]);
            $user->setId($item["id"]);
            $user->setRoles($item["roles"]);
            $users[] = $item;
        }
        return $users ;
    }

    public function getAllUserByEmail(string $email) : ? User {
        $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $parameters = [
            'email' => $email
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $user = new User($result["first_name"], $result["last_name"], $result["email"], $result["password"]);
            $user->setId($result["id"]);
            $user->setRoles($result["roles"]);
            return $user;
        }

        return null ;
    }

    public function getAllUserById(int $id) : ? User {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $user = new User($result["first_name"], $result["last_name"], $result["email"], $result["password"]);
            $user->setId($result["id"]);
            $user->setRoles($result["roles"]);
            return $user;
        }
        return null ;
    }

    /**********************************************************
                             * CHANGE USER INFORMATION *
    **********************************************************/

    public function changeName(int $id, string $first_name, string $last_name) : void {
        $query = $this->db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :id");
        $parameters = [
        'id' => $id,
        'first_name' => $first_name, 
        'last_name' => $last_name, 
        ];
        $query->execute($parameters);
    }

    public function changePassword(int $id, string $password) : void {
        $query = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $parameters = [
        'id' => $id,
        'password' => $password, 
        ];
        $query->execute($parameters);
    }

    public function changeEmail(int $id, string $email) : void {
        $query = $this->db->prepare("UPDATE users SET email = :email WHERE id = :id");
        $parameters = [
        'id' => $id,
        'email' => $email, 
        ];
        $query->execute($parameters);
    }
}
