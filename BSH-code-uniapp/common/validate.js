export function isEmpty(value){
  //  console.log('source',value);
      const type = typeof value;
      if(type === 'undefined') {
  //         console.log('检查到undefined')
          return true;
      }
      if(type === 'string' && value.replace(/(^[ \t\n\r]*)|([ \t\n\r]*$)/g,'').length === 0) {
  //         console.log('检查到空字符串')
          return true;
      }
      if(type === 'string' && (value ==='null' || value === null)) {
  //         console.log('检查到空字符串')
          return true;
      }
      if(type === 'number' && isNaN(value)) {
  //         console.log('检查到未知NaN数字对象')
          return true;
      }
      if(type === 'object' && (null === value|| value.length === 0)) {
  //         console.log('检查到空对象')
          return true;
      }   
      return false;
  }

