<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8"/>
  <title>Recherche</title>
  <style>
    table{width:100%;border-collapse:collapse}
    table tr,table th,table td{border:1px solid black;}
    table tr td{text-align:center;padding:1em;}
  </style>
</head>
<body>
  <?php
    # get connection credential for DB and SMTP server
    include('/var/www/dbUsers.php');
    include('/var/www/smtpUsers.php');
    
    # create connect function to reach DB
    $connect = new PDO('mysql:host='.$dbHost.';dbname='.$dbName,$dbUser,$dbPass,[
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
      ]);

    # initialize mailing
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Host     = $smtpHost;   //Adresse IP ou DNS du serveur SMTP
    $mail->Port     = $smtpPort;   //Port TCP du serveur SMTP
    $mail->SMTPAuth = True;        //Utiliser l'identification
    $mail->CharSet  = 'UTF-8';
    
    if($mail->SMTPAuth){
       $mail->SMTPSecure = $smtpSecure; //Protocole de sécurisation des échanges avec le SMTP
       $mail->Username   = $smtpUser;   //Adresse email à utiliser
       $mail->Password   = $smtpPass;   //Mot de passe de l'adresse email à utiliser
    }
    
    $mail->From       = $smtpUser;  // L'email à afficher pour l'envoi
    $mail->FromName   = $smptAlias; //L'alias de l'email de l'emetteur
    $mail->Subject    = "Tes informations adhérent·e"; //Le sujet du mail
    $mail->WordWrap   = 50;  //Nombre de caracteres pour le retour a la ligne automatique
    $mail->IsHTML(true);     //Préciser qu'il faut utiliser le texte brut
  ?>

  <form method='post'>
    <input type='text' placeholder='NOM' name="lastName" required/>
    <input type='text' placeholder='Prenom' name="firstName" required/>
    <input type='text' placeholder='Date de naissance (JJ/MM/AAAA)' name="DoB" required/>
    <input type='submit' value="Obtenir mes informations"/>
  </form>
  
  <table>
    <tbody>
      <?php
        # concatenate DB query
        if(!empty($_POST)){
          $sql='select * from adhesions2020';
          $params=[];
          if(isset($_POST['lastName'])){
            $sql.=' where NOM like :NOM';
            $params[':NOM']="%".addcslashes($_POST['lastName'],'_')."%";
            $sql.=' AND PRENOM like :PRENOM';
            $params[':PRENOM']="%".addcslashes($_POST['firstName'],'_')."%";
            $sql.=' AND NAISS like :NAISS';
            $params[':NAISS']="%".addcslashes($_POST['DoB'],'_')."%";
          }
          # Proceed with DB query 
          $resultats=$connect->prepare($sql);
          $resultats->execute($params);
          # One single result is what we want. Send information to corresponding email address
          if($resultats->rowCount()==1){
            $d=$resultats->fetch(PDO::FETCH_ASSOC);
            $email=trim($d['EMAIL']);
            $corpsDuMail="
              <ul>
               <li> <strong> Date d'inscription : <strong> ".$d['DATE_INSCRIPTION']."
               <li> <strong> Licence OK ? : <strong> ".$d['LICENCE_OK']."
               <li> <strong> Nom : <strong> ".$d['NOM']."
               <li> <strong> Prénom : <strong> ".$d['PRENOM']."
               <li> <strong> Date de naissance : <strong> ".$d['NAISS']."
               <li> <strong> Sexe : <strong> ".$d['SEXE']."
               <li> <strong> Adresse1 : <strong> ".$d['ADRESSE']."
               <li> <strong> Adresse2 : <strong> ".$d['ADD2']."
               <li> <strong> Adresse3 : <strong> ".$d['ADD3']."
               <li> <strong> Code Postal : <strong> ".$d['CP']."
               <li> <strong> Ville : <strong> ".$d['VILLE']."
               <li> <strong> Assurance : <strong> ".$d['ASSUR']."
               <li> <strong> Téléphone fixe : <strong> ".$d['TELDOM']."
               <li> <strong> Téléphone pro  : <strong> ".$d['TELPRO']."
               <li> <strong> Téléphone : <strong> ".$d['TELEPHONE']."
               <li> <strong> E-mail : <strong> ".$d['EMAIL']."
               <li> <strong> Numéro de Licence : <strong> ".$d['NUM_LICENCE']."
               <li> <strong> Statut de l'adhésion : <strong> ".$d['STATUT']."
               <li> <strong> Certifical OK ? : <strong> ".$d['CERTIF_OK']."
               <li> <strong> Date du dernier certificat : <strong> ".$d['DATE_CERTIF']."
               <li> <strong> Assurage Escalade : <strong> ".$d['DEBUTANTS']."
               <li> <strong> Numéro d'urgence : <strong> ".$d['URGENCE']."
              </ul>";
            $mail->AddAddress($email);
            $mail->Body    = $corpsDuMail;  //Texte HTML
            if(!$mail->send()) {
              echo "<ul><li> ERREUR ! ".$mail->ErrorInfo.
                   "<li> NOM : ".$_POST['lastName'].
                   "<li> Prénom : ".$_POST['firstName'].
                   "<li> Date de Naissance : ".$_POST['DoB'].
                   "</ul>";
            } else {
              echo "Les informations vous concernant ont été envoyées à votre adresse email"; // l'adresse ",$email;
            }
            $resultats->closeCursor();
          }
          # Otherwise, explain error
          else {
            if($resultats->rowCount()>1) {
              echo '<tr><td colspan=4> Plusieurs résultats correspondent à ces données. Affine ta recherche.</td></tr>'.$connect=null;
            }
            else {
              echo '<tr><td colspan=4>aucun résultat trouvé</td></tr>'.$connect=null;
            }
          }
        }
        else {
          echo '<tr>Procéder à la recherche en renseignant au moins un des champs ci-dessus</tr>';
        }
      ?>
    </tbody>
  </table>
</body>
</html>
