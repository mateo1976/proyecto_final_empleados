// public/js/main.js - JavaScript mejorado

document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== AUTO-CERRAR ALERTAS ====================
    autoCloseAlerts();
    
    // ==================== ANIMACIONES DE CONTADORES ====================
    animateCounters();
    
    // ==================== TOOLTIPS DE BOOTSTRAP ====================
    initTooltips();
    
    // ==================== CONFIRMACIONES DE ELIMINACIÓN ====================
    confirmDeletes();
    
    // ==================== VALIDACIÓN DE FORMULARIOS ====================
    validateForms();
    
    // ==================== TABLA DATATABLES ====================
    initDataTables();
    
});

/**
 * Auto-cerrar alertas después de 5 segundos
 */
function autoCloseAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

/**
 * Animar contadores numéricos
 */
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    
    counters.forEach(counter => {
        const target = parseFloat(counter.textContent.replace(/[^0-9.-]/g, ''));
        const duration = 2000; // 2 segundos
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = formatNumber(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = formatNumber(target);
            }
        };
        
        // Iniciar animación cuando el elemento sea visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        observer.observe(counter);
    });
}

/**
 * Formatear número según el contenido original
 */
function formatNumber(num) {
    // Si tiene símbolo de dinero
    if (num >= 1000) {
        return Math.floor(num).toLocaleString('es-CO');
    }
    return Math.floor(num);
}

/**
 * Inicializar tooltips de Bootstrap
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Confirmaciones personalizadas para eliminación
 */
function confirmDeletes() {
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Si ya tiene onclick, no hacer nada
            if (this.hasAttribute('onclick')) return;
            
            e.preventDefault();
            
            const nombre = this.getAttribute('data-nombre') || 'este registro';
            
            if (confirm(`¿Estás seguro de eliminar ${nombre}?\n\nEsta acción no se puede deshacer.`)) {
                window.location.href = this.href;
            }
        });
    });
}

/**
 * Validación mejorada de formularios
 */
function validateForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor completa todos los campos requeridos');
                return false;
            }
            
            // Validar emails
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value && !isValidEmail(field.value)) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    e.preventDefault();
                    alert('Por favor ingresa un correo electrónico válido');
                }
            });
            
            // Validar números negativos
            const numberFields = form.querySelectorAll('input[type="number"]');
            numberFields.forEach(field => {
                if (field.hasAttribute('min') && parseFloat(field.value) < parseFloat(field.min)) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    e.preventDefault();
                    alert('Los valores numéricos no pueden ser menores al mínimo permitido');
                }
            });
            
            // Mostrar indicador de carga
            if (isValid) {
                showLoadingIndicator(form);
            }
        });
        
        // Quitar clase is-invalid al escribir
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });
}

/**
 * Validar email
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Mostrar indicador de carga al enviar formulario
 */
function showLoadingIndicator(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
        submitBtn.disabled = true;
        
        // Restaurar después de 10 segundos (por si hay error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    }
}

/**
 * Inicializar DataTables si existe jQuery
 */
function initDataTables() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        // Configuración ya se hace en cada vista específica
        // Esta función es un placeholder para configuración global futura
    }
}

/**
 * Función helper para formatear dinero
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(amount);
}

/**
 * Función helper para formatear fechas
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).format(date);
}

/**
 * Copiar al portapapeles
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copiado al portapapeles', 'success');
    }).catch(err => {
        console.error('Error al copiar:', err);
    });
}

/**
 * Mostrar toast notification (requiere Bootstrap 5)
 */
function showToast(message, type = 'info') {
    // Crear toast dinámicamente
    const toastHTML = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Agregar al DOM
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    
    // Inicializar y mostrar
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Eliminar del DOM después de ocultar
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

/**
 * Debounce para búsquedas
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Scroll suave a un elemento
 */
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Exportar funciones para uso global
window.AppUtils = {
    formatCurrency,
    formatDate,
    copyToClipboard,
    showToast,
    debounce,
    scrollToElement
};
