# Use Node.js official image
FROM node:22-alpine

# Set working directory
WORKDIR /app

# Copy package.json and package-lock.json (or yarn.lock)
COPY react_app/package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the app files
COPY ./react_app .

# Build React app for production
RUN npm run build

# Expose the port
EXPOSE 3000

# Command to run the React app
CMD ["npm", "start"]
