-- Esquema inicial de la Intranet Corporativa Modular
CREATE DATABASE IF NOT EXISTS intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE intranet;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) NULL
);

INSERT INTO roles (id, name, slug, description) VALUES
    (1, 'Administrador Principal', 'admin-principal', 'Control total del sistema.'),
    (2, 'Publicador', 'publicador', 'CRUD limitado a módulos de contenido.'),
    (3, 'Usuario Final', 'usuario-final', 'Acceso de solo lectura.');

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles (id)
);

INSERT INTO users (name, email, password, role_id) VALUES
    ('Administrador General', 'admin@intranet.test', '$2y$10$InEn3Nu1So7kHm4Y8mJ4YO7YVpWwfvX2gYdExgkt1/3uzMdGII4XG', 1),
    ('Publicador Corporativo', 'publicador@intranet.test', '$2y$10$InEn3Nu1So7kHm4Y8mJ4YO7YVpWwfvX2gYdExgkt1/3uzMdGII4XG', 2),
    ('Usuario Invitado', 'usuario@intranet.test', '$2y$10$InEn3Nu1So7kHm4Y8mJ4YO7YVpWwfvX2gYdExgkt1/3uzMdGII4XG', 3);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT NOT NULL,
    image_path VARCHAR(255) NULL,
    published_at DATETIME NOT NULL,
    author_id INT,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    position VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50) NULL,
    extension VARCHAR(10) NULL,
    birthday DATE NULL
);

CREATE TABLE org_nodes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    position VARCHAR(150) NOT NULL,
    department VARCHAR(150) NULL,
    parent_id INT NULL,
    photo_path VARCHAR(255) NULL,
    FOREIGN KEY (parent_id) REFERENCES org_nodes (id) ON DELETE SET NULL
);

CREATE TABLE quick_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    url VARCHAR(255) NOT NULL,
    target VARCHAR(20) DEFAULT '_blank',
    visibility ENUM('global', 'personal') DEFAULT 'personal',
    category VARCHAR(100) DEFAULT 'Generales',
    user_id INT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE embedded_sites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    url VARCHAR(255) NOT NULL,
    layout VARCHAR(50) DEFAULT 'grid',
    width INT DEFAULT 480,
    height INT DEFAULT 320
);

CREATE TABLE document_folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES document_folders(id) ON DELETE SET NULL
);

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    folder_id INT NULL,
    visibility VARCHAR(50) DEFAULT 'todos',
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (folder_id) REFERENCES document_folders(id) ON DELETE SET NULL
);

CREATE TABLE branding_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NOT NULL
);

INSERT INTO branding_settings (`key`, `value`) VALUES
    ('site_name', 'Intranet Corporativa Modular'),
    ('primary_color', '#0d6efd'),
    ('secondary_color', '#6610f2'),
    ('accent_color', '#198754'),
    ('background_color', '#f5f7fb'),
    ('font_family', '"Poppins", "Segoe UI", sans-serif'),
    ('logo_path', '/resources/img/logo.svg');

CREATE TABLE user_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    preferences JSON NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE metrics (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` BIGINT DEFAULT 0
);
INSERT INTO metrics (`key`, `value`) VALUES ('visits', 0) ON DUPLICATE KEY UPDATE value = value;

CREATE TABLE sessions (
    user_id INT PRIMARY KEY,
    user_name VARCHAR(150) NOT NULL,
    last_seen DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO events (title, event_date, description) VALUES
    ('Kickoff de Q1', DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'Reunión general para revisar objetivos del trimestre.'),
    ('Capacitación en Ciberseguridad', DATE_ADD(CURDATE(), INTERVAL 12 DAY), 'Sesión de concientización sobre buenas prácticas.');

INSERT INTO announcements (title, content, image_path, published_at, author_id) VALUES
    ('Actualización de beneficios', 'Conoce las nuevas prestaciones para colaboradores.', NULL, NOW(), 1),
    ('Programa de voluntariado', 'Participa en las actividades sociales del mes.', NULL, NOW(), 2);

INSERT INTO contacts (name, position, email, phone, extension, birthday) VALUES
    ('Camila Ortega', 'Directora General', 'camila.ortega@empresa.com', '555-0101', '100', '1985-02-18'),
    ('Juan Paredes', 'Líder de TI', 'juan.paredes@empresa.com', '555-0102', '105', '1990-07-09'),
    ('Maria Rojas', 'Analista de RH', 'maria.rojas@empresa.com', '555-0103', '120', '1992-11-22');

INSERT INTO org_nodes (name, position, department, parent_id) VALUES
    ('Camila Ortega', 'CEO', 'Dirección General', NULL),
    ('Juan Paredes', 'CTO', 'Tecnología', 1),
    ('Maria Rojas', 'CHRO', 'Recursos Humanos', 1);

INSERT INTO quick_links (name, url, target, visibility, category, user_id) VALUES
    ('Correo Corporativo', 'https://outlook.office.com', '_blank', 'global', 'Generales', NULL),
    ('Mesa de Ayuda', 'https://helpdesk.empresa.com', '_blank', 'global', 'Generales', NULL),
    ('Mis vacaciones', 'https://intranet.empresa.com/vacaciones', '_blank', 'personal', 'Mis enlaces', 3);

INSERT INTO embedded_sites (name, url, layout, width, height) VALUES
    ('Panel de métricas', 'https://datastudio.google.com', 'grid', 480, 360),
    ('CRM', 'https://crm.empresa.com', 'grid', 480, 360);

INSERT INTO document_folders (name, parent_id) VALUES
    ('Manuales', NULL),
    ('Políticas', NULL);

INSERT INTO documents (name, file_path, folder_id, visibility) VALUES
    ('Manual del colaborador', '/storage/uploads/documents/manual_colaborador.pdf', 1, 'todos'),
    ('Política de seguridad', '/storage/uploads/documents/politica_seguridad.pdf', 2, 'todos');
