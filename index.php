<?php
require_once 'crypto.php';

// Built-in offline Male & Female dummy avatars (matching your reference image)
function getAvatarSrc($photo, $gender) {
    if (!empty($photo) && file_exists($photo)) {
        return $photo;
    }
    if ($gender === 'Female') {
        return "data:image/svg+xml;utf8," . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" fill="#fbbf24"/><path d="M25 85 C25 65 75 65 75 85 Z" fill="#ef4444"/><path d="M30 45 C30 22 70 22 70 45 L72 62 L28 62 Z" fill="#451a03"/><circle cx="50" cy="43" r="16" fill="#fed7aa"/></svg>');
    }
    return "data:image/svg+xml;utf8," . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50" fill="#2dd4bf"/><path d="M22 88 C22 66 78 66 78 88 Z" fill="#f97316"/><path d="M33 38 C33 22 67 22 67 38 Z" fill="#1e293b"/><circle cx="50" cy="43" r="16" fill="#fed7aa"/></svg>');
}

$search   = trim($_GET['search'] ?? '');
$category = $_GET['category'] ?? '';
if (!in_array($category, ['Family', 'Work', 'Friends'])) $category = '';

$page   = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$limit  = 12;
$offset = ($page - 1) * $limit;
$searchParam = "%" . $search . "%";

// Build filtered query
if ($category !== '') {
    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM contacts WHERE name LIKE ? AND category = ?");
    $countStmt->bind_param("ss", $searchParam, $category);
    $stmt = $conn->prepare("SELECT id, name, gender, category, phone, email, address, photo FROM contacts WHERE name LIKE ? AND category = ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssii", $searchParam, $category, $limit, $offset);
} else {
    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM contacts WHERE name LIKE ?");
    $countStmt->bind_param("s", $searchParam);
    $stmt = $conn->prepare("SELECT id, name, gender, category, phone, email, address, photo FROM contacts WHERE name LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $searchParam, $limit, $offset);
}

$countStmt->execute();
$totalRows = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = max(1, ceil($totalRows / $limit));
$countStmt->close();

$stmt->execute();
$result = $stmt->get_result();

// Fetch 3 most recent contacts for "Recent Activity" panel
$recentResult = $conn->query("SELECT name, gender, photo, updated_at FROM contacts ORDER BY updated_at DESC LIMIT 3");
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Book System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard">
    <!-- Left Dark Navy Sidebar -->
    <aside class="sidebar">
        <div>
            <div class="brand">
                <div class="brand-icon">AB</div>
                <div>
                    <h2>ADDRESS BOOK</h2>
                    <span>Smart Digital System</span>
                </div>
            </div>

            <nav class="nav-menu">
                <a href="index.php" class="nav-item <?= $category === '' ? 'active' : '' ?>">
                    <span>All Contacts</span>
                    <span class="nav-count"><?= (int)$totalRows ?></span>
                </a>
                <a href="add.php" class="nav-item">
                    <span>+ Add Contact</span>
                </a>
            </nav>

            <div class="sidebar-section">
                <h4>CATEGORIES</h4>
                <a href="?category=Family" class="cat-link <?= $category === 'Family' ? 'active-cat' : '' ?>">
                    <span class="cat-dot family"></span> Family
                </a>
                <a href="?category=Work" class="cat-link <?= $category === 'Work' ? 'active-cat' : '' ?>">
                    <span class="cat-dot work"></span> Work
                </a>
                <a href="?category=Friends" class="cat-link <?= $category === 'Friends' ? 'active-cat' : '' ?>">
                    <span class="cat-dot friends"></span> Friends
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="live-status"><span class="live-dot"></span> Live Sync Active</div>
            <div>AES-256 Encrypted DB</div>
        </div>
    </aside>

    <!-- Main Workspace -->
    <main class="main-area">
        <div class="top-header">
            <div>
                <h1>Contacts <?= $category ? "— " . htmlspecialchars($category) : "" ?></h1>
                <p>Active Records: <strong><?= (int)$totalRows ?></strong> (Click any card to view full details)</p>
            </div>
            <form method="GET" action="index.php" class="search-form">
                <?php if ($category): ?><input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>"><?php endif; ?>
                <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if ($search !== '' || $category !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">All</a>
                <?php endif; ?>
                <a href="add.php" class="btn btn-success">+ Add</a>
            </form>
        </div>

        <?php if ($msg === 'added'): ?>
            <div class="alert success">Contact saved and encrypted successfully!</div>
        <?php elseif ($msg === 'updated'): ?>
            <div class="alert success">Contact updated successfully!</div>
        <?php elseif ($msg === 'deleted'): ?>
            <div class="alert success">Contact deleted successfully!</div>
        <?php endif; ?>

        <div class="content-split">
            <!-- Middle Panel: Recent Activity -->
            <section class="recent-panel">
                <h3>Recent Activity</h3>
                <?php while ($rec = $recentResult->fetch_assoc()): ?>
                    <div class="recent-item">
                        <img src="<?= htmlspecialchars(getAvatarSrc($rec['photo'], $rec['gender']), ENT_QUOTES, 'UTF-8') ?>" alt="avatar">
                        <div>
                            <div class="recent-name"><?= htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="recent-time"><?= date('d M, h:i A', strtotime($rec['updated_at'])) ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </section>

            <!-- Right Panel: Compact Contact Cards Grid -->
            <section class="cards-section">
                <?php if ($result->num_rows > 0): ?>
                    <div class="cards-grid">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                                $decPhone   = decryptData($row['phone']);
                                $decEmail   = decryptData($row['email']);
                                $decAddress = decryptData($row['address']);
                                $avatarSrc  = getAvatarSrc($row['photo'], $row['gender']);
                            ?>
                            <div class="compact-card"
                                 onclick="openContactModal(this)"
                                 data-id="<?= (int)$row['id'] ?>"
                                 data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?>"
                                 data-gender="<?= htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8') ?>"
                                 data-category="<?= htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') ?>"
                                 data-phone="<?= htmlspecialchars($decPhone, ENT_QUOTES, 'UTF-8') ?>"
                                 data-email="<?= htmlspecialchars($decEmail, ENT_QUOTES, 'UTF-8') ?>"
                                 data-address="<?= htmlspecialchars($decAddress, ENT_QUOTES, 'UTF-8') ?>"
                                 data-avatar="<?= htmlspecialchars($avatarSrc, ENT_QUOTES, 'UTF-8') ?>">
                                <img class="card-avatar" src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Profile">
                                <h3><?= htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="card-phone"><?= htmlspecialchars($decPhone, ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="cat-badge <?= strtolower($row['category']) ?>"><?= htmlspecialchars($row['category']) ?></span>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-box">No contacts found. Click "+ Add" to create one.</div>
                <?php endif; ?>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= urlencode($category) ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>

<!-- Click-to-View Full Contact Detail Modal (matching the floating Alice Johnson card) -->
<div id="contactModal" class="modal-overlay" onclick="if(event.target === this) closeContactModal()">
    <div class="modal-card">
        <button class="modal-close" onclick="closeContactModal()">&times;</button>
        <div class="modal-header">
            <img id="mAvatar" src="" alt="Avatar">
            <div>
                <h2 id="mName"></h2>
                <div class="modal-tags">
                    <span id="mCategory" class="cat-badge"></span>
                    <span id="mGender" class="gender-badge"></span>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <p><strong>Mobile:</strong> <span id="mPhone"></span></p>
            <p><strong>Email:</strong> <span id="mEmail"></span></p>
            <p><strong>Address:</strong> <span id="mAddress"></span></p>
        </div>
        <div class="modal-actions">
            <a id="mEditBtn" href="#" class="btn btn-primary">Edit Contact</a>
            <a id="mDeleteBtn" href="#" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this contact?');">Delete</a>
        </div>
    </div>
</div>

<script>
    function openContactModal(card) {
        document.getElementById('mAvatar').src = card.dataset.avatar;
        document.getElementById('mName').textContent = card.dataset.name;
        document.getElementById('mPhone').textContent = card.dataset.phone;
        document.getElementById('mEmail').textContent = card.dataset.email;
        document.getElementById('mAddress').textContent = card.dataset.address;
        document.getElementById('mGender').textContent = card.dataset.gender;

        const catEl = document.getElementById('mCategory');
        catEl.textContent = card.dataset.category;
        catEl.className = 'cat-badge ' + card.dataset.category.toLowerCase();

        document.getElementById('mEditBtn').href = 'edit.php?id=' + card.dataset.id;
        document.getElementById('mDeleteBtn').href = 'delete.php?id=' + card.dataset.id;

        document.getElementById('contactModal').classList.add('show');
    }

    function closeContactModal() {
        document.getElementById('contactModal').classList.remove('show');
    }

    // Live SSE Sync
    let lastState = null;
    const evtSource = new EventSource("stream.php");
    evtSource.onmessage = function(event) {
        if (lastState === null) {
            lastState = event.data;
        } else if (lastState !== event.data) {
            location.reload();
        }
    };
</script>
</body>
</html>
<?php $stmt->close(); ?>