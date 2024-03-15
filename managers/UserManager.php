<?php 

class UserManager extends AbstractManager{

    public function SignUpUser(Users $users) : void {
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

    public function getAllUser() : array {
        $query = $this->db->prepare("SELECT * FROM users");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach($result as $item){
            $user = new Users($item["first_name"], $item["last_name"], $item["email"], $item["password"]);
            $user->setId($item["id"]);
            $user->setRoles($item["roles"]);
            $users[] = $item;
        }
        return $users ;
    }

    public function getAllUserByEmail(string $email) : ? Users {
        $query = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $parameters = [
            'email' => $email
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $user = new Users($result["first_name"], $result["last_name"], $result["email"], $result["password"]);
            $user->setId($result["id"]);
            $user->setRoles($result["roles"]);
            return $user;
        }

        return null ;
    }

    public function getAllUserById(int $id) : ? Users {
        $query = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result) {
            $user = new Users($result["first_name"], $result["last_name"], $result["email"], $result["password"]);
            $user->setId($result["id"]);
            $user->setRoles($result["roles"]);
            return $user;
        }

        return null ;
    }
}
