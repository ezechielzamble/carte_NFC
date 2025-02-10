<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <!-- Inclusion de Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    /* Styles globaux */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: #f7f7f7;
    }
    .admin-container {
      max-width: 1200px;
      margin: 0 auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      position: relative;
    }
    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: #e91e63;
      color: white;
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 4px;
      font-size: 0.9rem;
      transition: background 0.3s;
    }
    .logout-btn:hover {
      background: #e91e62;
    }
    /* Conteneur en colonne */
    .content-wrapper {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 60px; /* pour laisser de l'espace pour le bouton de déconnexion */
    }
    /* Panneau d'édition */
    .editor-panel {
      width: 100%;
    }
    /* Panneau d'aperçu centré, avec largeur maximale de 400px */
    .preview-panel {
      width: 100%;
      max-width: 400px;
      margin: 0 auto;
    }
    .preview-panel h3 {
      text-align: center;
      margin-bottom: 10px;
    }
    /* Styles de la carte de prévisualisation (similaires à page1) */
    .card-preview {
      position: relative;
      background: #ede7f6;
      border-radius: 16px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      padding: 20px;
      width: 100%;
      text-align: center;
      margin: 0 auto;
    }
    .card-preview img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      margin-bottom: 15px;
      object-fit: cover;
    }
    .card-preview h1 {
      font-size: 1.8em;
      color: #000;
      margin: 10px 0;
      font-family: 'Cursive', sans-serif;
    }
    .card-preview p {
      color: #555;
      font-size: 0.95em;
      margin: 5px 0 15px;
    }
    /* Boutons de contact dans l'aperçu :
       - Taille réduite,
       - Icône à gauche,
       - Texte centré */
    .card-preview .link-button {
      position: relative;
      background-color: #FFF9C4;
      color: #4A148C;
      border-radius: 2.5rem;
      padding: 0.5rem 0.8rem;
      text-decoration: none;
      font-weight: bold;
      margin-bottom: 0.5rem;
      box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
      font-size: 0.75rem;
      display: block;
      overflow: hidden;
    }
    .card-preview .link-button .icon {
      position: absolute;
      left: 8px;
      top: 50%;
      transform: translateY(-50%);
      width: 24px;
      height: 24px;
    }
    .card-preview .link-button .text {
      display: block;
      text-align: center;
      padding-left: 40px; /* espace pour l'icône */
    }
    /* Styles de l'éditeur (formulaire et contacts) */
    .editor-panel form div {
      margin-bottom: 15px;
    }
    .editor-panel form label {
      display: block;
      margin-bottom: 5px;
      color: #555;
      font-weight: bold;
    }
    .editor-panel form input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
      margin-bottom: 5px;
    }
    /* Barre d'outils pour l'éditeur enrichi */
    #editor-toolbar {
      display: flex;
      flex-wrap: nowrap;
      gap: 10px;
      padding: 5px;
      background-color: #f7f4f4;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-bottom: 10px;
    }
    #editor-toolbar button {
      padding: 5px 10px;
      border: none;
      cursor: pointer;
      border-radius: 3px;
      font-size: 0.9rem;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
      transition: background 0.3s;
    }
    #editor-toolbar button:hover {
      background-color: #e0e0e0;
    }
    /* Sélecteurs de couleur */
    input[type="color"] {
      -webkit-appearance: none;
      appearance: none;
      width: 40px;
      height: 40px;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      margin-left: 10px;
    }
    input[type="color"]::-webkit-color-swatch-wrapper {
      padding: 0;
    }
    input[type="color"]::-webkit-color-swatch {
      border: none;
      border-radius: 50%;
    }
    /* Zone de l'éditeur enrichi */
    #description {
      border: 1px solid #ddd;
      padding: 10px;
      min-height: 100px;
      box-sizing: border-box;
      border-radius: 5px;
      margin-bottom: 5px;
      outline: none;
    }
    .color-picker-group {
      display: flex;
      gap: 20px;
      align-items: flex-end;
      margin-bottom: 15px;
    }
    .color-picker {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .color-picker label {
      margin-bottom: 5px;
      font-weight: bold;
    }
    /* Boutons "Remove" */
    .remove-btn {
      background: #f44336;
      color: white;
      border: none;
      border-radius: 3px;
      padding: 5px 10px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: background 0.3s;
      margin-top: 5px;
    }
    .remove-btn:hover {
      background: #d32f2f;
    }
    /* Boutons généraux dans l'éditeur */
    .editor-panel button {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 5px;
      background: #e91e63;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
      margin-top: 10px;
    }
    .editor-panel button:hover {
      background: #d81b60;
    }
    /* Liste des contacts dans l'éditeur */
    .contacts-list {
      margin-top: 20px;
    }
    .contacts-list ul {
      list-style-type: none;
      padding: 0;
    }
    .contacts-list ul li {
      padding: 10px;
      background: #f9f9f9;
      margin-bottom: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .contacts-list ul li div button {
      margin-left: 5px;
      padding: 5px 10px;
      font-size: 0.8rem;
      cursor: pointer;
      border: none;
      border-radius: 3px;
      background: #2196F3;
      color: white;
      transition: background 0.3s;
    }
    .contacts-list ul li div button:hover {
      background: #1976D2;
    }
    /* Responsive styles */
    @media screen and (max-width: 768px) {
      .admin-container {
        padding: 10px;
      }
      #editor-toolbar button {
        padding: 5px;
        font-size: 0.8rem;
      }
      .card-preview {
        padding: 15px;
      }
      .card-preview h1 {
        font-size: 1.5em;
      }
      .card-preview p {
        font-size: 0.9em;
      }
      .link-button {
        font-size: 0.7rem;
        padding: 0.4rem 0.6rem;
      }
    }
  </style>
</head>
<body>
  <div class="admin-container">
    <!-- Bouton de déconnexion -->
    <a href="logout.php" class="logout-btn">Déconnexion</a>
    
    <div class="content-wrapper">
      <!-- Panneau Éditeur -->
      <div class="editor-panel">
        <!-- Affichage du username dans le titre -->
        <h2>Admin Panel - Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <form id="adminForm">
          <!-- Section Nom -->
          <div>
            <label for="name">Name:</label>
            <input type="text" id="name" placeholder="Enter name">
            <button type="button" class="remove-btn" onclick="removeName()">Remove Name</button>
          </div>
          
          <!-- Section Description avec éditeur enrichi -->
          <div>
            <label for="description">Description:</label>
            <div id="editor-toolbar">
              <button type="button" onclick="execCmd('bold')"><b>B</b></button>
              <button type="button" onclick="execCmd('italic')"><i>I</i></button>
              <button type="button" onclick="execCmd('underline')"><u>U</u></button>
              <button type="button" onclick="execCmd('insertUnorderedList')">&bull; List</button>
              <button type="button" onclick="execCmd('insertOrderedList')">1. List</button>
            </div>
            <div id="description" contenteditable="true" placeholder="Enter description"></div>
            <button type="button" class="remove-btn" onclick="removeDescription()">Remove Description</button>
          </div>
          
          <!-- Section Photo -->
          <div>
            <label for="photoInput">Photo:</label>
            <input type="file" id="photoInput" accept="image/*">
            <div class="preview" id="photoPreview"></div>
          </div>
          
          <!-- Sélecteurs de couleurs regroupés -->
          <div class="color-picker-group">
            <div class="color-picker">
              <label for="indexBg">Index Page Background:</label>
              <input type="color" id="indexBg" value="#ffe6f0" style="width: 25%">
            </div>
            <div class="color-picker">
              <label for="cardBg">Card Background:</label>
              <input type="color" id="cardBg" value="#ffffff" style="width: 27%">
            </div>
          </div>
          
          <!-- Section Contacts -->
          <div>
            <label for="contactType">Contact Type (ex: fa-phone, fa-envelope, fa-instagram, etc.):</label>
            <input type="text" id="contactType" placeholder="Enter Font Awesome icon class">
          </div>
          <div>
            <label for="contactContent">Contact Content:</label>
            <input type="text" id="contactContent" placeholder="Enter contact details (text or URL)">
          </div>
          <div>
            <label for="contactLabel">Contact Display Name:</label>
            <input type="text" id="contactLabel" placeholder="Enter display name for contact">
          </div>
          <button type="button" onclick="addContact()">Add / Update Contact</button>
        </form>
        
        <div class="contacts-list">
          <h3>Contacts List</h3>
          <ul id="contactsList"></ul>
        </div>
        
        <button type="button" onclick="saveData()">Save All Changes</button>
        <button type="button" onclick="resetData()">Reset Data</button>
      </div>
      
      <!-- Panneau Aperçu placé sous l'éditeur -->
      <div class="preview-panel">
        <h3>Aperçu de la Page</h3>
        <div class="card-preview" id="cardPreview">
          <!-- Boutons simulant "Ajouter aux contacts" et "Partager" (non actifs dans l'aperçu) -->
          <a class="contact-save" title="Ajouter aux contacts" style="position:absolute; top:10px; right:10px; color:#0000001f; font-size:1.8rem;"><i class="fa fa-address-book"></i></a>
          <a class="contact-share" title="Partager les contacts" style="position:absolute; top:10px; right:60px; color:#0000001f; font-size:1.8rem;"><i class="fa fa-share-alt"></i></a>
          <img id="preview-photo" src="" alt="Photo">
          <h1 id="preview-name">Nom de la page</h1>
          <p id="preview-description">Description de la page</p>
          <div class="preview-contacts" id="preview-contacts">
            <!-- Les contacts seront générés ici -->
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Fonction de formatage pour l'éditeur enrichi
    function execCmd(command) {
      document.execCommand(command, false, null);
      updatePreview();
    }
    
    // Variables globales
    let contacts = [];
    let editIndex = -1;
    let photoData = "";
    
    // Fonction de mise à jour de l'aperçu en live
    function updatePreview() {
      const name = document.getElementById('name').value;
      const description = document.getElementById('description').innerHTML;
      const cardBg = document.getElementById('cardBg').value;
      
      const previewName = document.getElementById('preview-name');
      const previewDescription = document.getElementById('preview-description');
      const previewPhoto = document.getElementById('preview-photo');
      const cardPreview = document.getElementById('cardPreview');
      
      previewName.textContent = name || "Nom de la page";
      previewDescription.innerHTML = description || "Description de la page";
      previewPhoto.src = photoData ? photoData : "";
      cardPreview.style.backgroundColor = cardBg;
      
      // Mise à jour de l'aperçu des contacts
      const previewContacts = document.getElementById('preview-contacts');
      previewContacts.innerHTML = "";
      contacts.forEach(contact => {
        let iconHtml = "";
        switch(contact.type) {
          case 'fa-phone':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/phone.png" alt="Phone Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-envelope':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/email.png" alt="Email Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-instagram':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/instagram-new.png" alt="Instagram Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-facebook':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/facebook-new.png" alt="Facebook Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-spotify':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/spotify.png" alt="Spotify Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-linkedin':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/linkedin.png" alt="Linkedin Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-whatsapp':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/whatsapp.png" alt="Whatsapp Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-youtube':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/youtube.png" alt="Youtube Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          case 'fa-chain':
            iconHtml = '<img src="https://img.icons8.com/ios-filled/50/4A148C/chain.png" alt="Chain Icon" style="width:24px;height:24px;border-radius:50%;">';
            break;
          default:
            iconHtml = "";
        }
        const a = document.createElement('a');
        a.className = "link-button";
        a.innerHTML = `<span class="icon">${iconHtml}</span><span class="text">${contact.label}</span>`;
        previewContacts.appendChild(a);
      });
    }
    
    // Mise à jour en temps réel sur certains champs
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('description').addEventListener('input', updatePreview);
    document.getElementById('cardBg').addEventListener('input', updatePreview);
    
    // Chargement des données du profil au démarrage
    document.addEventListener('DOMContentLoaded', () => {
      fetch('api/getProfile.php')
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            console.error(data.error);
            return;
          }
          document.getElementById('name').value = data.name || "";
          document.getElementById('description').innerHTML = data.description || "";
          document.getElementById('indexBg').value = data.indexBg || '#ffe6f0';
          document.getElementById('cardBg').value = data.cardBg || '#ffffff';
          if (data.photo) {
            photoData = data.photo;
            displayPhotoPreview(data.photo);
          }
          contacts = data.contacts || [];
          renderContacts();
          updatePreview();
        })
        .catch(err => console.error(err));
    });
    
    // Gestion du chargement de la photo
    document.getElementById('photoInput').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          photoData = e.target.result;
          displayPhotoPreview(photoData);
          updatePreview();
        }
        reader.readAsDataURL(file);
      }
    });
    
    function displayPhotoPreview(dataURL) {
      const preview = document.getElementById('photoPreview');
      preview.innerHTML = `<img src="${dataURL}" alt="Photo preview" style="max-width:150px;border-radius:50%;">`;
    }
    
    function removeName() {
      document.getElementById('name').value = '';
      updatePreview();
    }
    
    function removeDescription() {
      document.getElementById('description').innerHTML = '';
      updatePreview();
    }
    
    function addContact() {
      const type = document.getElementById('contactType').value.trim();
      const content = document.getElementById('contactContent').value.trim();
      const label = document.getElementById('contactLabel').value.trim();
      
      if (!type || !content || !label) {
        alert('All contact fields are required!');
        return;
      }
      
      if (editIndex === -1) {
        contacts.push({ type, content, label });
      } else {
        contacts[editIndex] = { type, content, label };
        editIndex = -1;
      }
      
      renderContacts();
      updatePreview();
      
      document.getElementById('contactType').value = '';
      document.getElementById('contactContent').value = '';
      document.getElementById('contactLabel').value = '';
    }
    
    function renderContacts() {
      const list = document.getElementById('contactsList');
      list.innerHTML = "";
      contacts.forEach((contact, index) => {
        const li = document.createElement('li');
        li.innerHTML = `<span><i class="fa ${contact.type}"></i> ${contact.label}</span>
                        <div>
                          <button onclick="editContact(${index})">Edit</button>
                          <button onclick="removeContact(${index})">Remove</button>
                        </div>`;
        list.appendChild(li);
      });
      updatePreview();
    }
    
    function editContact(index) {
      const contact = contacts[index];
      document.getElementById('contactType').value = contact.type;
      document.getElementById('contactContent').value = contact.content;
      document.getElementById('contactLabel').value = contact.label;
      editIndex = index;
      updatePreview();
    }
    
    function removeContact(index) {
      contacts.splice(index, 1);
      if (editIndex === index) {
        editIndex = -1;
        document.getElementById('contactType').value = '';
        document.getElementById('contactContent').value = '';
        document.getElementById('contactLabel').value = '';
      }
      renderContacts();
      updatePreview();
    }
    
    function saveData() {
      const name = document.getElementById('name').value;
      const description = document.getElementById('description').innerHTML;
      const indexBg = document.getElementById('indexBg').value;
      const cardBg = document.getElementById('cardBg').value;
      
      const payload = {
        name,
        description,
        photo: photoData,
        indexBg,
        cardBg,
        contacts
      };
      
      fetch('api/saveProfile.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Data saved successfully!');
        } else {
          alert('Error: ' + data.error);
        }
      })
      .catch(err => console.error(err));
    }
    
    function resetData() {
      fetch('api/resetProfile.php')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert("Data reset successfully!");
            location.reload();
          } else {
            alert("Error: " + data.error);
          }
        })
        .catch(err => console.error(err));
    }
  </script>
</body>
</html>
