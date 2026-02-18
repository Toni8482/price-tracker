FROM node:18-slim

WORKDIR /app

RUN apt-get update && apt-get install -y \
    chromium \
    fonts-noto-cjk \
    fonts-noto-color-emoji \
    libappindicator3-1 \
    libasound2 \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libcups2 \
    libdbus-1-3 \
    libgbm1 \
    libgl1-mesa-glx \
    libglib2.0-0 \
    libgomp1 \
    libgstreamer-plugins-base1.0-0 \
    libgstreamer1.0-0 \
    libgtk-3-0 \
    libnspr4 \
    libnss3 \
    libopengl0 \
    libpango-1.0-0 \
    libpangocairo-1.0-0 \
    libpulse0 \
    libxcb-dri3-0 \
    libxcomposite1 \
    libxdamage1 \
    libxext6 \
    libxfixes3 \
    libxkbcommon0 \
    libxrandr2 \
    libxrender1 \
    libu2f-udev \
    libvulkan1 \
    libx11-6 \
    libx11-xcb1 \
    libxcb1 \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

COPY package*.json ./
RUN npm install

COPY . .

EXPOSE 3000

CMD ["npm", "start"]
