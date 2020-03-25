import { TestBed } from '@angular/core/testing';

import { LiquidacionJuanitoService } from './liquidacion-juanito.service';

describe('LiquidacionJuanitoService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LiquidacionJuanitoService = TestBed.get(LiquidacionJuanitoService);
    expect(service).toBeTruthy();
  });
});
