// fichier JS de la page Cave

// Fonctionnalité de recherche
document.getElementById('searchWine').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const wineCards = document.querySelectorAll('.wine-card');
    
    wineCards.forEach(card => {
        const wineName = card.dataset.name;
        if (wineName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Fonctionnalité de filtrage
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Retirer la classe active de tous les boutons
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        // Ajouter la classe active au bouton cliqué
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        const wineCards = document.querySelectorAll('.wine-card');
        
        wineCards.forEach(card => {
            if (filter === 'all' || card.dataset.color === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Fonction de confirmation de suppression
function confirmDelete(wineId, wineName) {
    document.getElementById('wineName').textContent = wineName;
    document.getElementById('confirmDeleteBtn').onclick = function() {
        alert('Suppression du vin ID: ' + wineId + ' - Fonctionnalité à implémenter');
        closeDeleteModal();
    };
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Fermer la modal en cliquant à l'extérieur
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Fonction pour afficher les détails du vin dans une modal
document.addEventListener('DOMContentLoaded', function () {
    // Ouvre la modal de détail
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const wineCard = this.closest('.wine-card');
            showWineDetailFromCard(wineCard);
        });
    });

    // Ferme la modal si on clique à l'extérieur de la card
    document.getElementById('wineDetailModal').addEventListener('click', function (e) {
        if (e.target === this) closeWineDetail();
    });
});

function showWineDetailFromCard(card) {
    if (!card) return;
    const name = card.dataset.name || '';
    const image = card.dataset.image || '';
    const region = card.dataset.region || '';
    const ancien = card.dataset.ancien || '';
    const color = card.dataset.color || '';
    const producteur = card.dataset.producteur || '';
    const purchasePrice = card.dataset.purchasePrice || '';
    const degres = card.dataset.degres || '';
    const gout = card.dataset.gout || '';

    let html = `
        <button class="close-btn" onclick="closeWineDetail()">&times;</button>
        ${image ? `<img src="${image}" alt="${name}" class="wine-detail-img">` : `
            <div class="wine-detail-img" style="background: linear-gradient(135deg, #8B0000, #DC143C); display: flex; align-items: center; justify-content: center; height: 180px;">
                <i class="fas fa-wine-bottle" style="color: white; font-size: 48px;"></i>
            </div>
        `}
        <h2>${name}</h2>
        <div class="wine-detail-row"><i class="fas fa-map-marker-alt"></i> <strong>Région :</strong> ${region || 'Non spécifiée'}</div>
        <div class="wine-detail-row"><i class="fas fa-calendar-alt"></i> <strong>Millésime :</strong> ${ancien || 'Non spécifié'}</div>
        <div class="wine-detail-row"><i class="fas fa-palette"></i> <strong>Couleur :</strong> ${color || 'Non spécifiée'}</div>
        <div class="wine-detail-row"><i class="fas fa-industry"></i> <strong>Producteur :</strong> ${producteur || 'Non spécifié'}</div>
        <div class="wine-detail-row"><i class="fas fa-tag"></i> <strong>Prix :</strong> ${purchasePrice ? purchasePrice + '€' : 'Non spécifié'}</div>
        <div class="wine-detail-row"><i class="fas fa-percentage"></i> <strong>Degrés :</strong> ${degres || 'Non spécifié'}${degres ? '°' : ''}</div>
        ${gout ? `<div class="wine-description">${gout}</div>` : ''}
    `;

    document.getElementById('wineDetailContent').innerHTML = html;
    document.getElementById('wineDetailModal').style.display = 'flex';
}

function closeWineDetail() {
    document.getElementById('wineDetailModal').style.display = 'none';
}