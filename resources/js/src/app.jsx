import React from 'react';
import { createRoot } from 'react-dom/client';

function App() {
  return <h1>Hello from Vite + React!</h1>;
}

const container = document.getElementById('app') || document.body.appendChild(document.createElement('div'));
container.id = 'app';
const root = createRoot(container);
root.render(<App />);
