export const infoConst = () => {
    const companyLocal = sessionStorage.getItem('VmsSiteLocal')
  
    return {
      site: companyLocal,
    }
  }
  
  
  