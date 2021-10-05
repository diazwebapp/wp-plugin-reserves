let rest_settings =  wp_rest_api
window.addEventListener('load',async ()=>{
    
    const element_1 = document.querySelector('.step_1')
    const element_2 = document.querySelector('.step_2')
    const element_3 = document.querySelector('.step_3')
    const btn_regresar = document.querySelector('#btn_to_step_1')
    const btn_regresar_2 = document.querySelector('#btn_to_step_2')
    const btn_siguiente = document.querySelector('#btn_to_step_3')
    const btn_finish = document.querySelector('#btn_finish')
    const btn_buscar_casas = document.querySelector("#btn_buscar_casas")
    const calendar_container = document.querySelector('#calendarCheckin')
    
    btn_buscar_casas.addEventListener('click',async()=>{
        btn_buscar_casas.disabled = true
        const inputs = verificar_inputs({container_inputs:element_1})
        if(!inputs) return  btn_buscar_casas.disabled = false
        const {tarifa,reserve_dates_server} =  await consultar_reserva({api:rest_settings,persons:inputs['cant_huespedes']})
        if(!tarifa){
            return alert('no hay casas disponibles')
        }
        btn_buscar_casas.disabled = false
        
        element_2.classList.add('step_show')
        const calendar = print_calendar({calendar_container,reserve_dates_server})
        
        btn_siguiente.addEventListener('click',async()=>{
            btn_siguiente.disabled = true
            const {start,end} = calendar.getRange()
            
            const {reserve_dates,cargos_especiales} =  await consultar_reserva({api:rest_settings,persons:inputs['cant_huespedes'],checkin:start,checkout:end})
            const {factura,cliente} = process_data_reserve({tarifa,reserve_dates,cargos_especiales,inputs,checkin:start,checkout:end})
            btn_siguiente.disabled = false
            if(factura,cliente,start,end){
                element_3.classList.add('step_show')
                element_2.classList.remove('step_show')
                element_3.querySelectorAll('li')[0].textContent += cliente.nombre
                element_3.querySelectorAll('li')[1].textContent += cliente.correo
                element_3.querySelectorAll('li')[2].textContent += cliente.telefono
                element_3.querySelectorAll('li')[3].textContent += factura.casa
                element_3.querySelectorAll('li')[4].textContent += factura.checkin
                element_3.querySelectorAll('li')[5].textContent += factura.checkout
                element_3.querySelectorAll('li')[6].textContent += factura.huespedes
                element_3.querySelector('p').textContent += factura.total + ' EUR'
                
                btn_finish.addEventListener('click',async()=>{
                    btn_finish.disabled = true
                    const {status} = await crear_reservacion({api:rest_settings,factura,cliente})
                    btn_finish.disabled = false
                    if(status){
                        alert('Gracias por reservar con nosotros!, nos pondremos en contacto con usted para confirmar su reserva')
                        window.location.reload()
                    }                        
                })
            }
        })  
    })
    btn_regresar.addEventListener('click',async()=>{
        window.location.reload()
    })
    
    btn_regresar_2.addEventListener('click',async()=>{
        window.location.reload()
        //element_3.classList.remove('step_show')
        //element_2.classList.add('step_show')
    })
})
const verificar_inputs =({container_inputs})=>{
    const inputs = container_inputs.querySelectorAll('input')
    for(input of inputs){
        if(input.value==""){
            alert('Rellene el campo: '+input.name)
            return false
        }
        inputs[input.name] = input.value
    }
    
    const textareas = container_inputs.querySelectorAll('textarea')
    inputs['observaciones'] = textareas[0].value
    return inputs
}

const print_calendar = ({calendar_container,reserve_dates_server})=>{
    calendar_container.innerHTML = ""
    const my_calendar = new TavoCalendar('#calendarCheckin', {
        format: "YYYY-MM-DD",
        locale: "de",
        range_select: true,
        blacklist:reserve_dates_server
    })
    return my_calendar
}
const create_factura = ({inputs,tarifa,checkin,checkout,price})=>{
    
    const factura = {
        huespedes:inputs['cant_huespedes'],
        observaciones:inputs['observaciones'],
        checkin,
        checkout,
        tarifa:tarifa.post_title,
        casa:tarifa.meta['casa'][0],
        total:price
   }
   const cliente ={
        nombre:inputs['nombre'],
        telefono:inputs['telefono'],
        correo:inputs['correo']
    }

   return {factura,cliente}
}
const calcular_costos = ({reserve_dates,cargos_especiales,tarifa_days_array})=>{
    let price = 0
    let date_detail = {}
    
    reserve_dates.pop()
    for(dia of reserve_dates){
        const convert = new Date(dia)
        let current = convert.getUTCDay()
        price += parseFloat(tarifa_days_array[current])
        date_detail[dia] = price
    }
    
    for(bono of cargos_especiales){
        if(date_detail[bono.fecha_especial]){
            price += parseFloat(bono.monto)
        }
    }
    console.log(reserve_dates)
    return {date_detail, price}
}
const process_data_reserve = ({tarifa,reserve_dates,cargos_especiales,inputs,checkin,checkout})=>{
 
    const tarifa_days_array = [tarifa.meta['day_0'],tarifa.meta['day_1'],tarifa.meta['day_2'],tarifa.meta['day_3'],tarifa.meta['day_4'],tarifa.meta['day_5'],tarifa.meta['day_6']]
    const {price} = calcular_costos({reserve_dates,cargos_especiales,tarifa_days_array})
    const {factura,cliente} = create_factura({tarifa,inputs,checkin,checkout,price})
    return {factura,cliente}
}
/** HTTP Methods */

const consultar_reserva = async({api,checkin,checkout,persons})=>{ 
        const req = await fetch(`${api.root}wp-reserves-system/v1/prepare_reserve?persons=${persons}${checkin?'&checkin='+checkin:''}${checkout?'&checkout='+checkout:''}`)
        return await req.json()
}
const crear_reservacion = async({api,factura,cliente})=>{ 
    const confirmar = window.confirm('Desea realizar la reserva?')
    if(confirmar){
        const req_h = await fetch(`${api.root}wp-reserves-system/v1/create_reserve`,{
            method:'POST',
            body:JSON.stringify({factura,cliente}),
            headers:{
                "content-type":"application/json"
            }
        })
        return await req_h.json()
    }
}
