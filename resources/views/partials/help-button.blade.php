<!-- Floating Help Button -->
<div class="help-button-container">
    <div class="btn-group dropup">
        <button type="button" class="btn btn-gradient-purple btn-circle btn-lg shadow-lg"
                data-bs-toggle="dropdown" aria-expanded="false"
                title="Bantuan & Informasi">
            <i class="bi bi-question-circle-fill"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><h6 class="dropdown-header"><i class="bi bi-info-circle me-2"></i>Bantuan & Informasi</h6></li>
            <li><a class="dropdown-item" href="{{ route('guide.student') }}">
                <i class="bi bi-book me-2 text-primary"></i> Panduan Siswa
            </a></li>
            <li><a class="dropdown-item" href="{{ route('guide.teacher') }}">
                <i class="bi bi-mortarboard me-2 text-primary"></i> Panduan Guru
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('about.developer') }}">
                <i class="bi bi-people me-2 text-primary"></i> Tentang Pengembang
            </a></li>
        </ul>
    </div>
</div>

<style>
.help-button-container {
    position: fixed;
    bottom: 2rem;
    right: 0;
    z-index: 1050;
}

.btn-gradient-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
}

.btn-gradient-purple:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    transform: translateY(-3px);
}

.btn-circle {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.help-button-container .dropdown-menu {
    min-width: 250px;
    border-radius: 0.75rem;
    border: none;
    margin-bottom: 0.5rem;
}

.help-button-container .dropdown-item {
    padding: 0.75rem 1.25rem;
    transition: all 0.2s ease;
}

.help-button-container .dropdown-item:hover {
    background-color: #f3f4f6;
    padding-left: 1.5rem;
}

.help-button-container .dropdown-header {
    font-weight: 600;
    color: #667eea;
    padding: 0.75rem 1.25rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .help-button-container {
        bottom: 1rem;
        right: 0;
    }

    .btn-circle {
        width: 3rem;
        height: 3rem;
        font-size: 1.25rem;
    }

    .help-button-container .dropdown-menu {
        min-width: 220px;
    }
}

/* Animation */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(102, 126, 234, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
}

.btn-gradient-purple {
    animation: pulse 2s infinite;
}

.btn-gradient-purple:hover {
    animation: none;
}
</style>
