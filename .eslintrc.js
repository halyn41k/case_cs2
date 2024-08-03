module.exports = {
    root: true,
    env: {
      node: true, // Add this line
      browser: true,
      es2021: true,
    },
    extends: [
      'plugin:vue/essential',
      'eslint:recommended',
    ],
    parserOptions: {
      ecmaVersion: 12,
      sourceType: 'module',
    },
    rules: {
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    },
    overrides: [
      {
        files: [
          'babel.config.js',
          'vue.config.js',
          'webpack.config.js',
          '.eslintrc.js',
        ],
        env: {
          node: true, // Apply Node.js environment to these files
        },
        rules: {
          'no-undef': 'off', // Disable no-undef rule for these files
        },
      },
    ],
  };
  