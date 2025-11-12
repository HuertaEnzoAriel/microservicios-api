/**
 * Configuración de la aplicación
 */
const API_BASE_URL = 'http://localhost:8000/api';

/**
 * Referencias a elementos del DOM
 */
const elements = {
    loading: document.getElementById('loading'),
    content: document.getElementById('content'),
    error: document.getElementById('error'),
    errorMessage: document.getElementById('errorMessage'),
    apiMessage: document.getElementById('apiMessage'),
    apiVersion: document.getElementById('apiVersion'),
    apiTimestamp: document.getElementById('apiTimestamp'),
    protectedSection: document.getElementById('protectedSection'),
    adminSection: document.getElementById('adminSection'),
    btnLogin: document.getElementById('btnLogin'),
    btnLogout: document.getElementById('btnLogout')
};

/**
 * Estado de la aplicación
 */
const appState = {
    isAuthenticated: false,
    isAdmin: false,
    user: null
};

/**
 * Clase para manejar las peticiones a la API
 */
class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
    }

    /**
     * Realiza una petición GET a la API
     * @param {string} endpoint - Endpoint de la API
     * @returns {Promise<Object>} Respuesta de la API
     */
    async get(endpoint) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error en la petición:', error);
            throw error;
        }
    }

    /**
     * Realiza una petición POST a la API
     * @param {string} endpoint - Endpoint de la API
     * @param {Object} body - Cuerpo de la petición
     * @returns {Promise<Object>} Respuesta de la API
     */
    async post(endpoint, body) {
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(body)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error en la petición:', error);
            throw error;
        }
    }
}

/**
 * Instancia del cliente API
 */
const apiClient = new ApiClient(API_BASE_URL);

/**
 * Clase para manejar la interfaz de usuario
 */
class UIManager {
    /**
     * Muestra el estado de carga
     */
    showLoading() {
        elements.loading.style.display = 'flex';
        elements.content.style.display = 'none';
        elements.error.style.display = 'none';
    }

    /**
     * Muestra el contenido principal
     */
    showContent() {
        elements.loading.style.display = 'none';
        elements.content.style.display = 'block';
        elements.error.style.display = 'none';
    }

    /**
     * Muestra un mensaje de error
     * @param {string} message - Mensaje de error
     */
    showError(message) {
        elements.loading.style.display = 'none';
        elements.content.style.display = 'none';
        elements.error.style.display = 'block';
        elements.errorMessage.textContent = message;
    }

    /**
     * Actualiza el contenido con los datos de la API
     * @param {Object} data - Datos de la API
     */
    updateContent(data) {
        elements.apiMessage.textContent = data.message;
        elements.apiVersion.textContent = data.version;
        elements.apiTimestamp.textContent = this.formatTimestamp(data.timestamp);
    }

    /**
     * Formatea un timestamp para mostrar
     * @param {string} timestamp - Timestamp ISO
     * @returns {string} Timestamp formateado
     */
    formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    /**
     * Actualiza la visibilidad de secciones según el estado de autenticación
     */
    updateAuthUI() {
        // Mostrar/ocultar botones de autenticación
        if (appState.isAuthenticated) {
            elements.btnLogin.style.display = 'none';
            elements.btnLogout.style.display = 'block';
            elements.protectedSection.style.display = 'block';
        } else {
            elements.btnLogin.style.display = 'block';
            elements.btnLogout.style.display = 'none';
            elements.protectedSection.style.display = 'none';
        }

        // Mostrar/ocultar sección de administrador
        if (appState.isAuthenticated && appState.isAdmin) {
            elements.adminSection.style.display = 'block';
        } else {
            elements.adminSection.style.display = 'none';
        }
    }
}

/**
 * Instancia del gestor de UI
 */
const uiManager = new UIManager();

/**
 * Controlador principal de la aplicación
 */
class AppController {
    /**
     * Inicializa la aplicación
     */
    async init() {
        console.log('Inicializando aplicación...');
        
        // Registrar event listeners
        this.registerEventListeners();
        
        // Cargar datos iniciales
        await this.loadLandingData();
        
        // Actualizar UI según estado de autenticación
        uiManager.updateAuthUI();
    }

    /**
     * Registra los event listeners de la aplicación
     */
    registerEventListeners() {
        // Botón de login (placeholder para siguiente tutorial)
        elements.btnLogin.addEventListener('click', () => {
            console.log('Login button clicked - Implementación en próximo tutorial');
            alert('La funcionalidad de autenticación se implementará en el siguiente tutorial');
        });

        // Botón de logout (placeholder para siguiente tutorial)
        elements.btnLogout.addEventListener('click', () => {
            console.log('Logout button clicked - Implementación en próximo tutorial');
            alert('La funcionalidad de cierre de sesión se implementará en el siguiente tutorial');
        });

        // Botón de acción de usuario (placeholder)
        const btnUserAction = document.getElementById('btnUserAction');
        if (btnUserAction) {
            btnUserAction.addEventListener('click', () => {
                console.log('User action button clicked');
                alert('Esta funcionalidad requiere autenticación');
            });
        }

        // Botón de acción de administrador (placeholder)
        const btnAdminAction = document.getElementById('btnAdminAction');
        if (btnAdminAction) {
            btnAdminAction.addEventListener('click', () => {
                console.log('Admin action button clicked');
                alert('Esta funcionalidad requiere privilegios de administrador');
            });
        }
    }

    /**
     * Carga los datos de bienvenida desde la API
     */
    async loadLandingData() {
        try {
            // Mostrar estado de carga
            uiManager.showLoading();

            // Realizar petición a la API
            const data = await apiClient.get('/landing');

            // Verificar que la respuesta sea exitosa
            if (data.status === 'success') {
                uiManager.updateContent(data);
                uiManager.showContent();
            } else {
                throw new Error('La respuesta de la API no fue exitosa');
            }

        } catch (error) {
            console.error('Error al cargar datos:', error);
            uiManager.showError(
                'No se pudo conectar con la API. Verifica que el servidor esté ejecutándose.'
            );
        }
    }
}

/**
 * Punto de entrada de la aplicación
 */
document.addEventListener('DOMContentLoaded', () => {
    const app = new AppController();
    app.init();
});