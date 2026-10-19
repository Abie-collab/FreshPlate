<?php
require_once "Db.php";

class Recipe extends Db{
    private $conn;

    public function __construct(){
        $this->conn = $this->connect();
    }

    public function fetch_recipes(){
        try{
            $sql = "SELECT * FROM recipes";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $recipes;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function fetch_recipe_details($id){
        try{
            $sql = "SELECT * FROM recipes WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
            return $recipe;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function insert_recipe($title,$dsp,$inst,$prptime,$ctm,$serv,$img){
        try{
            $sql = "INSERT INTO recipes(title,description,instructions,prep_time,cook_time,servings,image) VALUES (?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
           $rcp = $stmt->execute([$title,$dsp,$inst,$prptime,$ctm,$serv,$img]);
           return $rcp; 
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function update_recipe($title,$dsp,$inst,$prptime,$ctm,$serv,$img,$id){
    try{
        $sql = "UPDATE recipes SET title=?,description=?,instructions=?,prep_time=?,cook_time=?,servings=?,image=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$title,$dsp,$inst,$prptime,$ctm,$serv,$img,$id]);
        return $stmt->rowCount() > 0;
    }catch(PDOException $e){
        return $e->getMessage();
    }
}

    public function delete_recipe($id){
        try{
            $sql = "DELETE FROM recipes WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

        public function fetch_recipe_tags($id){
        try{
            $sql = "SELECT t.id, t.name
                    FROM recipe_tags rt
                    JOIN tags t ON t.id = rt.tag_id
                    WHERE rt.recipe_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function fetch_recipe_ingredients($id){
        try{
            $sql = "SELECT i.name, ri.quantity, COALESCE(ri.unit_override, i.unit) AS unit
                    FROM recipe_ingredients ri
                    JOIN ingredients i ON i.id = ri.ingredient_id
                    WHERE ri.recipe_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(PDOException $e){
            return $e->getMessage();
        }
    }

    public function fetch_recipe_full($id){
        $recipe = $this->fetch_recipe_details($id);
        if(!$recipe){
            return null;
        }
        $recipe['tags'] = $this->fetch_recipe_tags($id);
        $recipe['ingredients'] = $this->fetch_recipe_ingredients($id);
        return $recipe;
    }

}

$rec = new Recipe;
// $res = $rec->fetch_recipes();
// $res = $rec->fetch_recipe_full(1);
// $res = $rec->delete_recipe(7);
// $res = $rec->insert_recipe('Vegetable Fried Rice', 'A quick, colorful fried rice loaded with mixed vegetables.', '1. Cook rice and let it cool completely (day-old rice works best).\n2. Heat oil in a wok, scramble eggs and set aside.\n3. Stir-fry diced carrots, peas, and spring onions until tender.\n4. Add rice, soy sauce, and scrambled eggs, tossing until evenly combined.\n5. Season with salt and pepper, serve hot.', 15, 15, 3, 'http://localhost/recipe_plan/assets/uploads/friedrice.jpg');
// $res = $rec->update_recipe('1','Vegetable Fried Rice', 'A quick, colorful fried rice loaded with mixed vegetables.', '1. Cook rice and let it cool completely (day-old rice works best).\n2. Heat oil in a wok, scramble eggs and set aside.\n3. Stir-fry diced carrots, peas, and spring onions until tender.\n4. Add rice, soy sauce, and scrambled eggs, tossing until evenly combined.\n5. Season with salt and pepper, serve hot.', 15, 15, 3, 'http://localhost/recipe_plan/assets/uploads/friedrice.jpg','7');
// echo "<pre>";
//   print_r($res);
//   echo "</pre>";