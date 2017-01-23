var QBApp = {
  appId: 39478,
  authKey: 'PnVxS7S-traYDDA',
  authSecret: 'xR6Bfs9Tdbktx9g'
};

var config = {
  chatProtocol: {
    active: 2
  },
  debug: {
    mode: 1,
    file: null
  }
};

QB.init(QBApp.appId, QBApp.authKey, QBApp.authSecret, config);