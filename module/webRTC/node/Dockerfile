FROM node:latest
RUN mkdir -p /usr/src/test
WORKDIR /usr/src/test
COPY package.json /usr/src/test/
RUN npm install
RUN npm install -g socket.io --save
RUN npm link socket.io
COPY . /usr/src/test
EXPOSE 55420
CMD npm start