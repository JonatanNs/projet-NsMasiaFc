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
}