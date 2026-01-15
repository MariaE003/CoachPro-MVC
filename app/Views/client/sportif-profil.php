<?php
require '../session.php';
$RolePage="client";

$id_user=$_SESSION["user_id"];
$req=$connect->prepare("select * from client where id_user=?");
$req->bind_param("s",$id_user);
$req->execute();
$res=$req->get_result()->fetch_assoc();
// echo $res["id"];
$erreur="";

if (isset($_POST["save"])) {
  if (!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["email"])  && !empty($_POST["telephone"])  && !empty($_POST["password"])) {
  $nom=$_POST["nom"];
  $prenom=$_POST["prenom"];
  $email=$_POST["email"];
  $telephone=$_POST["telephone"];
  $password=$_POST["password"];
  $passW_hasher=password_hash($password,PASSWORD_BCRYPT);

  $reqUpdate=$connect->prepare("UPDATE client SET email=?,nom=?,prenom=?,telephone=? where id_user=?");
  $reqUpdate->bind_param("ssssi",$email,$nom,$prenom,$telephone,$id_user);
  $reqUpdate->execute();
  //modifier user
  $reqUpdate=$connect->prepare("UPDATE users SET email=?,password=? where id=?");
  $reqUpdate->bind_param("ssi",$email,$passW_hasher,$id_user);
  $reqUpdate->execute();
  $erreur="Modification reussite !";

  }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


</head>
<body>
    <?php
    require('/FitCoach-Pro/Pages/components/header.php');

    ?>
    



<section class="max-w-4xl mx-auto px-6 py-16">
  <h1 class="text-3xl font-bold mb-8">Mon Profil</h1>

  <div class="bg-white rounded-xl shadow p-8 grid md:grid-cols-3 gap-8">

    
    <div class="flex flex-col items-center text-center space-y-4">

      <img
        src="./image.png"
        alt="Client"
        class="w-32 h-32 rounded-full object-cover shadow"
      >

      <div>
        <h2 class="text-xl font-semibold"><?=$res['prenom'].' '.$res['nom']?></h2>
        <p class="text-sm text-gray-500">Client</p>
      </div>

      <div class="w-full space-y-3 mt-4">
        
        <form action="" method="POST">
          <button name="logout" type="submit"
          class="w-full px-4 py-2 rounded-lg border border-red-500 text-red-500 hover:bg-red-500 hover:text-white transition">
          Déconnexion
        </button>
      </form>
    </div>
  </div>
  
  <!-- info-->
  <div class="md:col-span-2 space-y-6">
    
    <h3 class="text-xl font-semibold border-b pb-2">
      Modifier mes informations
    </h3>
    <?php if(!empty($erreur)){
              ?>
              <div class="bg-green-200 text-green-700 p-3 rounded mb-4">
                <?= $erreur ?>
              </div>
            <?php
            };?>
    
    <form class="grid md:grid-cols-2 gap-6" method="POST">
      
      <div>
        <label class="block text-sm text-gray-500 mb-1">Nom</label>
        <input
        type="text" value="<?=$res["nom"]?>" name="nom"
        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
        placeholder="Nom"
        >
      </div>
      
      <div>
        <label class="block text-sm text-gray-500 mb-1">Prénom</label>
        <input
        type="text" value="<?=$res["prenom"]?>" name="prenom"
        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
        placeholder="Prénom"
        >
      </div>
      
      <div>
        <label class="block text-sm text-gray-500 mb-1">Email</label>
        <input
        type="email" value="<?=$res["email"]?>" name="email"
        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
        placeholder="email@email.com"
          >
        </div>
        
        <div>
          <label class="block text-sm text-gray-500 mb-1">Téléphone</label>
          <input
          type="text" value="<?=$res["telephone"]?>" name="telephone"
          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
          placeholder="+212 6xx xx xx xx"
          >
        </div>
        <div>
          <label class="block text-sm text-gray-500 mb-1">Nouveau mot de passe</label>
          <input
          type="text" name="password"
          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
          placeholder="Hbd xgsj34"
          >
        </div>
        <div class="w-full space-y-3 mt-4">
          <label class="block text-sm text-gray-500 -mt-2"></label>
          <button type="submit" name="save"
            class="w-full px-4 border py-2 rounded-lg bg-green-500 text-white font-semibold m-x-auto hover:bg-green-600 transition ">
            Enregistrer les modifications
          </button>
          </div>
        </form>
      </div>

  </div>
</section>


    <?php
   require('/FitCoach-Pro/Pages/components/footer.php');

    ?>
</body>
</html>