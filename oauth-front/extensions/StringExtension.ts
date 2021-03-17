if (!String.format) {
  String.format = function(format) {
    let args = Array.prototype.slice.call(arguments, 1);
    return format.replace(/{(\d+)}/g, function(match: any, number: string | number) {
      // @ts-ignore
      return typeof args[number] != 'undefined' ? args[number] : match;
    });
  };
}

String.prototype.capitalize = function() {
  return this.charAt(0).toUpperCase() + this.slice(1);
}

export {}