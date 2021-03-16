import { TestBed } from '@angular/core/testing';

import { CumpleanioService } from './cumpleanio.service';

describe('CumpleanioService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: CumpleanioService = TestBed.get(CumpleanioService);
    expect(service).toBeTruthy();
  });
});
