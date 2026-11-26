<?php
require_once 'crypto.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$error = '';

$stmt = $conn->prepare("SELECT id, name, gender, category, phone, email, address, photo, version FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$contact = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$contact) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $gender   = $_POST['gender'] ?? 'Male';
    $category = $_POST['category'] ?? 'Friends';
    $phone    = trim($_POST['phone'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $version  = isset($_POST['version']) ? (int)$_POST['version'] : 0;
    $photoPath = $contact['photo'];

    if (!in_array($gender, ['Male', 'Female'])) $gender = 'Male';
    if (!in_array($category, ['Family', 'Work', 'Friends'])) $category = 'Friends';

    if ($name === '' || $phone === '' || $email === '' || $address === '') {
        $error = 'All fields are required.';
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = 'Phone number must be exactly 10 digits.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $fileName = 'uploads/' . uniqid('avatar_') . '.' . $ext;
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $fileName)) {
                    $photoPath = $fileName;
                }
            } else {
                $error = 'Only JPG, PNG, or WEBP images are allowed.';
            }
        }

        if ($error === '') {
            $encPhone   = encryptData($phone);
            $encEmail   = encryptData($email);
            $encAddress = encryptData($address);

            $stmt = $conn->prepare("UPDATE contacts SET name=?, gender=?, category=?, phone=?, email=?, address=?, photo=?, version=version+1 WHERE id=? AND version=?");
            $stmt->bind_param("sssssssii", $name, $gender, $category, $encPhone, $encEmail, $encAddress, $photoPath, $id, $version);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                $error = 'Conflict detected! Someone else edited this contact. Please reload and try again.';
            } else {
                header("Location: index.php?msg=updated");
                exit;
            }
            $stmt->close();
        }
    }
}

$name     = $contact['name'];
$gender   = $contact['gender'];
$category = $contact['category'];
$phone    = decryptData($contact['phone']);
$email    = decryptData($contact['email']);
$address  = decryptData($contact['address']);
$version  = $contact['version'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contact - Address Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Contact</h1>

    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= $id ?>" enctype="multipart/form-data" class="contact-form">
        <input type="hidden" name="version" value="<?= (int)$version ?>">

        <label>Full Name:</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>">

        <div class="form-row">
            <div>
                <label>Gender:</label>
                <select name="gender" required>
                    <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div>
                <label>Category:</label>
                <select name="category" required>
                    <option value="Family" <?= $category === 'Family' ? 'selected' : '' ?>>Family</option>
                    <option value="Work" <?= $category === 'Work' ? 'selected' : '' ?>>Work</option>
                    <option value="Friends" <?= $category === 'Friends' ? 'selected' : '' ?>>Friends</option>
                </select>
            </div>
        </div>

        <label>Mobile Number (10 digits):</label>
        <input type="text" name="phone" required maxlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="<?= htmlspecialchars($phone, ENT_QUOTES, 'UTF-8') ?>">

        <label>Email:</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">

        <label>Address:</label>
        <textarea name="address" rows="3" required><?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></textarea>

        <label>Change Profile Photo (Optional):</label>
        <input type="file" name="photo" accept="image/png, image/jpeg, image/webp">

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Contact</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>