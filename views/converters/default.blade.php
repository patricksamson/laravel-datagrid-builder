date: {
    from: function (value) { return Date.parse(value); },
    to: function (value) { return new Date(value).toISOString().split('T')[0]; }
},

datetime: {
    from: function (value) { return Date.parse(value); },
    to: function (value) {
        function pad(number) {
          if (number < 10) {
            return '0' + number;
          }
          return number;
        }

        var d = new Date(value);
        return d.getUTCFullYear() +
            '-' + pad(d.getUTCMonth() + 1) +
            '-' + pad(d.getUTCDate()) +
            ' ' + pad(d.getUTCHours()) +
            ':' + pad(d.getUTCMinutes()) +
            ':' + pad(d.getUTCSeconds());
    }
},
