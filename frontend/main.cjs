const { app, BrowserWindow } = require('electron');
const path = require('path');

function createWindow() {
  const win = new BrowserWindow({
    width: 1280,
    height: 820,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true
    }
  });

  const devUrl = 'http://localhost:5173';
  const distPath = path.join(__dirname, 'dist', 'index.html');

  // Cargar primero el dev server de Vite si está disponible; de lo contrario, cargar el build local
  win.loadURL(devUrl).catch(() => {
    win.loadFile(distPath).catch((err) => {
      console.error('No se pudo cargar la aplicación:', err);
    });
  });
}

app.whenReady().then(() => {
  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow();
    }
  });
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

