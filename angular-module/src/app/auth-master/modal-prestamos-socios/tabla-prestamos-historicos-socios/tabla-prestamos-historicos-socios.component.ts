import { Component, OnInit } from '@angular/core';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-prestamos-historicos-socios',
  templateUrl: './tabla-prestamos-historicos-socios.component.html',
  styleUrls: ['./tabla-prestamos-historicos-socios.component.css']
})
export class TablaPrestamosHistoricosSociosComponent implements OnInit {

  //objetos con valores de los select para ser manipulados
  selectAnio = {
    id: '1',
    descripcion: ''
  }

  selectMes = {
    id: '1',
    descripcion: ''
  }

  //variable que almacena todos los prestamos que tiene el servidor
  valoresPrestamosSalud;
  valoresPrestamosApuro;
  valoresPrestamosAporte
  montoDelInteresPagar;
  montoCuotaPagar;
  montoFinalPagar;

  //Variables de carga
  cargandoTabla = false;

  constructor(private _sindicalService: SindicalService, private _fechasService: AniosService) { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectAnio.id = '1';

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.selectMes.id = '1';

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        let AnioActual;
        AnioActual = response;
        this.selectAnio.id = AnioActual.id;
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        let mesActual;
        mesActual = response;
        this.selectMes.id = mesActual.id;
        this.refrescarTablaPrestamosClientes();
      },
      error => {
        console.log(error);
      }
    )
  }

  refrescarTablaPrestamosClientes() {
    this.cargandoTabla = true;
    this._sindicalService.getPrestamosHistoricos(this.selectAnio.id, this.selectMes.id).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.valoresPrestamosSalud = null;
          this.valoresPrestamosApuro = null;
          this.valoresPrestamosAporte = null;
          this.cargandoTabla = false;
          alert(response.mensaje);
        } else {
          this.valoresPrestamosSalud = response.salud;
          this.valoresPrestamosApuro = response.apuro;
          this.valoresPrestamosAporte = response.aporte;
          this.cargandoTabla = false;
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(evento) {
    this.selectAnio.id = evento.target.value;
    console.log(this.selectAnio.id);
    this.refrescarTablaPrestamosClientes();
  }

  changeMes(evento) {
    this.selectMes.id = evento.target.value;
    console.log(this.selectMes.id);
    this.refrescarTablaPrestamosClientes();
  }

}
