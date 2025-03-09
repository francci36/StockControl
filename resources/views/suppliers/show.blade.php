<!-- resources/views/suppliers/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Détails du Fournisseur</title>
</head>
<body>
    <h1>Détails du Fournisseur</h1>
    <p><strong>Nom :</strong> {{ $supplier->name }}</p>
    <p><strong>Contact :</strong> {{ $supplier->contact }}</p>
    <!-- Ajoutez ici d'autres détails du fournisseur si nécessaire -->
</body>
</html>
